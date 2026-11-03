<?php

namespace Database\Factories;

use App\Models\LayananKlinik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LayananKlinik>
 */
class LayananKlinikFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'category' => fake()->randomElement(['general', 'kia']),
            'description' => fake()->sentence(),
            'duration_minutes' => fake()->randomElement([30, 45, 60]),
            'image_path' => 'clinic-services/default.jpg',
            'is_active' => true,
        ];
    }
}
