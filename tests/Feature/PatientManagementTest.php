<?php

namespace Tests\Feature;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_filter_patients_by_age_and_gender(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $child = $this->createPatient(['name' => 'Anak Pasien', 'patient_category' => 'child', 'date_of_birth' => now()->subYears(10)->toDateString(), 'gender' => 'male']);
        $male = $this->createPatient(['name' => 'Pasien Laki-laki', 'patient_category' => 'male', 'date_of_birth' => now()->subYears(30)->toDateString(), 'gender' => 'male']);
        $female = $this->createPatient(['name' => 'Pasien Perempuan', 'patient_category' => 'female', 'date_of_birth' => now()->subYears(25)->toDateString(), 'gender' => 'female']);

        $this->actingAs($admin)
            ->get(route('patients.index', ['category' => 'child']))
            ->assertOk()
            ->assertSee($child->name)
            ->assertDontSee($male->name)
            ->assertDontSee($female->name);

        $this->actingAs($admin)
            ->get(route('patients.index', ['category' => 'male']))
            ->assertOk()
            ->assertSee($child->name)
            ->assertSee($male->name)
            ->assertDontSee($female->name);

        $this->actingAs($admin)
            ->get(route('patients.index', ['category' => 'female']))
            ->assertOk()
            ->assertSee($female->name)
            ->assertDontSee($male->name);
    }

    public function test_admin_can_filter_patients_by_general_and_kia_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $generalPatient = $this->createPatient(['name' => 'Pasien Umum', 'service_category' => 'general']);
        $kiaPatient = $this->createPatient(['name' => 'Pasien KIA', 'service_category' => 'kia']);

        $this->actingAs($admin)
            ->get(route('patients.index', ['category' => 'general']))
            ->assertOk()
            ->assertSee($generalPatient->name)
            ->assertDontSee($kiaPatient->name);

        $this->actingAs($admin)
            ->get(route('patients.index', ['category' => 'kia']))
            ->assertOk()
            ->assertSee($kiaPatient->name)
            ->assertDontSee($generalPatient->name);
    }

    public function test_child_patient_requires_guardian_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('patients.store'), $this->patientData([
                'date_of_birth' => now()->subYears(10)->toDateString(),
                'patient_category' => 'child',
                'gender' => 'male',
            ]))
            ->assertSessionHasErrors('guardian_name');
    }

    public function test_adult_female_patient_requires_husband_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('patients.store'), $this->patientData(['patient_category' => 'female', 'gender' => 'female']))
            ->assertSessionHasErrors('husband_name');
    }

    public function test_adult_male_patient_requires_wife_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('patients.store'), $this->patientData(['patient_category' => 'male', 'gender' => 'male']))
            ->assertSessionHasErrors('wife_name');
    }

    /** @return array<string, mixed> */
    private function patientData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Nama Pasien',
            'phone' => '081234567890',
            'date_of_birth' => now()->subYears(30)->toDateString(),
            'patient_category' => 'male',
            'service_category' => 'general',
            'gender' => 'male',
        ], $overrides);
    }

    private function createPatient(array $overrides = []): Pasien
    {
        return Pasien::create(array_merge($this->patientData(), $overrides));
    }
}
