<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\KategoriAlat;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('welcome', [
            'role' => auth()->user()->role,
            'stats' => [
                'users' => User::query()->count(),
                'kategori' => KategoriAlat::query()->count(),
                'alat' => Alat::query()->count(),
                'peminjaman' => Peminjaman::query()->count(),
                'pengembalian' => Pengembalian::query()->count(),
                'log' => LogAktivitas::query()->count(),
            ],
            'alat' => Alat::query()
                ->with('kategoriAlat:id,nama')
                ->latest()
                ->limit(8)
                ->get(),
            'peminjaman' => Peminjaman::query()
                ->with(['peminjam:id,name', 'petugas:id,name'])
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
