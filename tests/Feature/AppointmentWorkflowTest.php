<?php

namespace Tests\Feature;

use App\Models\JanjiTemu;
use App\Models\LayananKlinik;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_request_is_pending_and_uses_their_own_patient_record(): void
    {
        $patient = $this->createPatient('Akun Sendiri');
        $otherPatient = $this->createPatient('Pasien Lain');
        $doctor = User::factory()->create(['name' => 'dr. Budi', 'role' => 'admin']);
        $service = LayananKlinik::factory()->create(['name' => 'Konsultasi umum', 'category' => 'general']);
        $user = User::factory()->create(['patient_id' => $patient->id]);

        $this->actingAs($user)
            ->post(route('appointments.store'), [
                'patient_id' => $otherPatient->id,
                'service_id' => $service->id,
                'appointment_at' => now()->addDays(3)->format('Y-m-d\\TH:i'),
                'doctor' => $doctor->name,
                'type' => 'Pemeriksaan rutin',
                'status' => 'confirmed',
            ])
            ->assertRedirect(route('appointments.index'));

        $this->assertDatabaseHas('appointments', [
            'patient_id' => $patient->id,
            'service_id' => $service->id,
            'status' => 'scheduled',
            'doctor' => $doctor->name,
        ]);
        $this->assertDatabaseMissing('appointments', ['patient_id' => $otherPatient->id]);
    }

    public function test_admin_can_confirm_then_complete_a_pending_appointment(): void
    {
        $patient = $this->createPatient('Pasien Test');
        $appointment = $this->createAppointment($patient);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('appointments.status', $appointment), ['status' => 'confirmed'])
            ->assertRedirect();

        $this->assertDatabaseHas('appointments', ['id' => $appointment->id, 'status' => 'confirmed']);

        $this->patch(route('appointments.status', $appointment), ['status' => 'completed'])
            ->assertRedirect();

        $this->assertDatabaseHas('appointments', ['id' => $appointment->id, 'status' => 'completed']);
    }

    public function test_patient_can_request_a_new_time_for_a_confirmed_appointment(): void
    {
        $patient = $this->createPatient('Pasien Test');
        $appointment = $this->createAppointment($patient);
        $appointment->update(['status' => 'confirmed']);
        $user = User::factory()->create(['patient_id' => $patient->id]);

        $this->actingAs($user)
            ->put(route('appointments.update', $appointment), [
                'appointment_at' => now()->addDays(5)->format('Y-m-d\\TH:i'),
                'type' => 'Pemeriksaan lanjutan',
                'status' => 'confirmed',
            ])
            ->assertRedirect(route('appointments.index'));

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'scheduled',
            'type' => 'Pemeriksaan lanjutan',
        ]);
    }

    public function test_patient_cannot_view_another_patients_appointment(): void
    {
        $owner = $this->createPatient('Pemilik Janji');
        $otherPatient = $this->createPatient('Pasien Lain');
        $appointment = $this->createAppointment($owner);
        $user = User::factory()->create(['patient_id' => $otherPatient->id]);

        $this->actingAs($user)
            ->get(route('appointments.show', $appointment))
            ->assertForbidden();
    }

    public function test_patient_can_cancel_appointment_more_than_24_hours_ahead(): void
    {
        $patient = $this->createPatient('Pasien Test');
        $appointment = $this->createAppointment($patient);
        $user = User::factory()->create(['patient_id' => $patient->id]);

        $this->actingAs($user)
            ->patch(route('appointments.cancel', $appointment))
            ->assertRedirect(route('appointments.index'));

        $this->assertDatabaseHas('appointments', ['id' => $appointment->id, 'status' => 'cancelled']);
    }

    public function test_patient_cannot_update_appointment_status_directly(): void
    {
        $patient = $this->createPatient('Pasien Test');
        $appointment = $this->createAppointment($patient);
        $user = User::factory()->create(['patient_id' => $patient->id]);

        $this->actingAs($user)
            ->patch(route('appointments.status', $appointment), ['status' => 'confirmed'])
            ->assertForbidden();

        $this->assertDatabaseHas('appointments', ['id' => $appointment->id, 'status' => 'scheduled']);
    }

    private function createPatient(string $name): Pasien
    {
        return Pasien::create([
            'name' => $name,
            'phone' => '081234567890',
            'patient_category' => 'female',
            'service_category' => 'general',
            'gender' => 'female',
        ]);
    }

    private function createAppointment(Pasien $patient): JanjiTemu
    {
        return JanjiTemu::create([
            'patient_id' => $patient->id,
            'appointment_at' => now()->addDays(3),
            'doctor' => 'dr. Budi',
            'type' => 'Kontrol umum',
            'status' => 'scheduled',
        ]);
    }
}
