<?php

namespace Database\Factories;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LogAktivitas>
 */
class LogAktivitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'aksi' => fake()->randomElement(['login', 'create', 'update', 'delete', 'approve', 'return']),
            'modul' => fake()->randomElement(['user', 'alat', 'kategori', 'peminjaman', 'pengembalian']),
            'subjek_type' => null,
            'subjek_id' => null,
            'data_lama' => null,
            'data_baru' => ['keterangan' => fake()->sentence()],
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}
