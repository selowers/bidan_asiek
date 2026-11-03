<?php

namespace Tests\Feature;

use App\Models\LayananKlinik;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('patients.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_access_clinic_resources(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('patients.index'))
            ->assertOk()
            ->assertSee('Pasien Umum')
            ->assertSee('Pasien KIA');
    }

    public function test_regular_users_cannot_access_admin_resources(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get(route('patients.index'))->assertForbidden();
    }

    public function test_user_can_register_only_with_existing_patient_email(): void
    {
        $patient = Pasien::factory()->create(['email' => 'patient@example.com']);

        $response = $this->post(route('register.store'), [
            'name' => $patient->name,
            'email' => $patient->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs(User::where('email', $patient->email)->first());
        $this->assertDatabaseHas('users', ['email' => $patient->email, 'role' => 'user', 'patient_id' => $patient->id]);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password123'])->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_patient_can_create_appointment_with_admin_doctor(): void
    {
        $patient = Pasien::factory()->create(['email' => 'patient@example.com']);
        $admin = User::factory()->create(['name' => 'dr. Budi', 'role' => 'admin']);
        $service = LayananKlinik::factory()->create(['name' => 'Konsultasi rutin']);
        $user = User::factory()->create([
            'name' => $patient->name,
            'email' => $patient->email,
            'role' => 'user',
            'patient_id' => $patient->id,
        ]);

        $this->actingAs($user)
            ->post(route('appointments.store'), [
                'patient_id' => $patient->id,
                'service_id' => $service->id,
                'appointment_at' => now()->addDays(3)->format('Y-m-d\\TH:i'),
                'doctor' => $admin->name,
                'type' => 'Konsultasi rutin',
                'status' => 'scheduled',
                'notes' => 'Mau kontrol rutin',
            ])
            ->assertRedirect(route('appointments.index'));

        $this->assertDatabaseHas('appointments', [
            'patient_id' => $patient->id,
            'service_id' => $service->id,
            'doctor' => $admin->name,
            'type' => 'Konsultasi rutin',
            'status' => 'scheduled',
        ]);
    }
}
