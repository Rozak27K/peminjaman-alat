<?php

namespace Database\Factories;

use App\Models\Alat;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DetailPeminjaman>
 */
class DetailPeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'peminjaman_id' => Peminjaman::factory(),
            'alat_id' => Alat::factory(),
            'jumlah' => fake()->numberBetween(1, 3),
            'kondisi_saat_pinjam' => 'baik',
            'kondisi_saat_kembali' => null,
            'catatan' => fake()->optional()->sentence(),
        ];
    }
}
