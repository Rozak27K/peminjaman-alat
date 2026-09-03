<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengembalian>
 */
class PengembalianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => fake()->unique()->bothify('PGB-########'),
            'peminjaman_id' => Peminjaman::factory(),
            'petugas_id' => User::factory()->petugas(),
            'tanggal_kembali' => fake()->dateTimeBetween('-1 week', 'now'),
            'status' => 'diproses',
            'denda' => fake()->randomFloat(2, 0, 50000),
            'catatan' => fake()->optional()->sentence(),
        ];
    }
}
