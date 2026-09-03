<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\View\View;

class LogAktivitasController extends Controller
{
    public function index(): View
    {
        return view('log-aktivitas.index', [
            'logs' => LogAktivitas::query()
                ->with('user:id,name')
                ->latest()
                ->paginate(15),
        ]);
    }
}
