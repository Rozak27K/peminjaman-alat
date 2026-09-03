<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::query()->firstOrCreate(['email' => 'petugas@example.com'], [
            'name' => 'Petugas',
            'password' => 'password',
            'role' => 'petugas',
        ]);

        User::query()->firstOrCreate(['email' => 'peminjam@example.com'], [
            'name' => 'Peminjam',
            'password' => 'password',
            'role' => 'peminjam',
        ]);

        $this->call([
            KategoriAlatSeeder::class,
            AlatSeeder::class,
        ]);
    }
}
