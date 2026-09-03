<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\KategoriAlat;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laptop = KategoriAlat::query()->where('kode', 'KAT-LPT')->first();
        $proyektor = KategoriAlat::query()->where('kode', 'KAT-PRJ')->first();
        $audio = KategoriAlat::query()->where('kode', 'KAT-AUD')->first();

        $alat = [
            ['kategori_alat_id' => $laptop?->id, 'kode' => 'ALT-LPT-001', 'nama' => 'Laptop Operasional', 'stok_total' => 5, 'stok_tersedia' => 5],
            ['kategori_alat_id' => $proyektor?->id, 'kode' => 'ALT-PRJ-001', 'nama' => 'Proyektor Kelas', 'stok_total' => 2, 'stok_tersedia' => 2],
            ['kategori_alat_id' => $audio?->id, 'kode' => 'ALT-AUD-001', 'nama' => 'Speaker Portable', 'stok_total' => 3, 'stok_tersedia' => 3],
        ];

        foreach ($alat as $item) {
            Alat::query()->firstOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
