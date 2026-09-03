<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalPengajuan = fake()->dateTimeBetween('-1 month', 'now');

        return [
            'kode' => fake()->unique()->bothify('PMJ-########'),
            'peminjam_id' => User::factory()->peminjam(),
            'petugas_id' => null,
            'tanggal_pengajuan' => $tanggalPengajuan,
            'tanggal_pinjam' => null,
            'tanggal_rencana_kembali' => fake()->dateTimeBetween($tanggalPengajuan, '+2 weeks'),
            'tanggal_disetujui' => null,
            'status' => 'diajukan',
            'keperluan' => fake()->sentence(),
            'catatan_peminjam' => fake()->optional()->sentence(),
            'catatan_petugas' => null,
        ];
    }
}
