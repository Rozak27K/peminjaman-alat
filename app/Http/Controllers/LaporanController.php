<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(): View
    {
        return view('laporan.index', [
            'statusPeminjaman' => Peminjaman::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->orderBy('status')
                ->get(),
            'totalDenda' => Pengembalian::query()->sum('denda'),
            'pengembalian' => Pengembalian::query()
                ->with('peminjaman.peminjam:id,name')
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }
}
