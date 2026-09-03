<?php

namespace Database\Seeders;

use App\Models\KategoriAlat;
use Illuminate\Database\Seeder;

class KategoriAlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['kode' => 'KAT-LPT', 'nama' => 'Laptop', 'deskripsi' => 'Perangkat laptop untuk kegiatan belajar dan kerja.'],
            ['kode' => 'KAT-PRJ', 'nama' => 'Proyektor', 'deskripsi' => 'Perangkat proyeksi untuk presentasi.'],
            ['kode' => 'KAT-AUD', 'nama' => 'Audio', 'deskripsi' => 'Perangkat audio pendukung acara.'],
        ];

        foreach ($kategori as $item) {
            KategoriAlat::query()->firstOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
