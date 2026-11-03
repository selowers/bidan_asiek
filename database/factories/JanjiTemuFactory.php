<?php

namespace Database\Factories;

use App\Models\JanjiTemu;
use App\Models\Pasien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JanjiTemu>
 */
class JanjiTemuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Pasien::factory(),
            'appointment_at' => now()->addDays(3),
            'doctor' => fake()->name(),
            'type' => 'Kontrol umum',
            'status' => 'scheduled',
            'notes' => null,
        ];
    }
}
