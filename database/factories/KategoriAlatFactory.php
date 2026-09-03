<?php

namespace Database\Factories;

use App\Models\KategoriAlat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KategoriAlat>
 */
class KategoriAlatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->randomElement(['Laptop', 'Proyektor', 'Kamera', 'Audio', 'Jaringan']).' '.fake()->unique()->numberBetween(1, 999),
            'kode' => fake()->unique()->bothify('KAT-###'),
            'deskripsi' => fake()->sentence(),
            'aktif' => true,
        ];
    }
}
