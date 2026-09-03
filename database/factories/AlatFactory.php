<?php

namespace Database\Factories;

use App\Models\Alat;
use App\Models\KategoriAlat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alat>
 */
class AlatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stokTotal = fake()->numberBetween(1, 20);

        return [
            'kategori_alat_id' => KategoriAlat::factory(),
            'kode' => fake()->unique()->bothify('ALT-####'),
            'nama' => fake()->words(3, true),
            'merk' => fake()->company(),
            'model' => fake()->bothify('MDL-###'),
            'stok_total' => $stokTotal,
            'stok_tersedia' => fake()->numberBetween(0, $stokTotal),
            'kondisi' => fake()->randomElement(['baik', 'rusak_ringan', 'rusak_berat']),
            'status' => 'tersedia',
            'deskripsi' => fake()->sentence(),
        ];
    }
}
