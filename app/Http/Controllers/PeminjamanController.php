<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\DetailPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = auth()->user();

        return view('peminjaman.index', [
            'peminjaman' => Peminjaman::query()
                ->with(['peminjam:id,name', 'petugas:id,name'])
                ->when($user->role === 'peminjam', fn ($query) => $query->where('peminjam_id', $user->id))
                ->latest()
                ->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = auth()->user();

        return view('peminjaman.form', [
            'peminjaman' => new Peminjaman,
            'peminjam' => $user->role === 'peminjam'
                ? collect([$user])
                : User::query()->where('role', 'peminjam')->orderBy('name')->get(['id', 'name']),
            'alat' => Alat::query()->where('stok_tersedia', '>', 0)->orderBy('nama')->get(['id', 'nama', 'kode', 'stok_tersedia']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'peminjam_id' => [$user->role === 'peminjam' ? 'nullable' : 'required', 'exists:users,id'],
            'alat_id' => ['required', 'exists:alat,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'tanggal_rencana_kembali' => ['required', 'date', 'after_or_equal:today'],
            'keperluan' => ['required', 'string'],
            'catatan_peminjam' => ['nullable', 'string'],
        ]);

        $peminjaman = DB::transaction(function () use ($data): Peminjaman {
            $peminjaman = Peminjaman::query()->create([
                'kode' => 'PMJ-'.now()->format('YmdHis'),
                'peminjam_id' => auth()->user()->role === 'peminjam' ? auth()->id() : $data['peminjam_id'],
                'tanggal_pengajuan' => today(),
                'tanggal_rencana_kembali' => $data['tanggal_rencana_kembali'],
                'status' => 'diajukan',
                'keperluan' => $data['keperluan'],
                'catatan_peminjam' => $data['catatan_peminjam'] ?? null,
            ]);

            DetailPeminjaman::query()->create([
                'peminjaman_id' => $peminjaman->id,
                'alat_id' => $data['alat_id'],
                'jumlah' => $data['jumlah'],
                'kondisi_saat_pinjam' => 'baik',
            ]);

            return $peminjaman;
        });

        $this->log('create', $peminjaman, null, $peminjaman->load('detailPeminjaman')->toArray());

        return redirect()->route('peminjaman.show', $peminjaman)->with('status', 'Pengajuan peminjaman dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman): View
    {
        abort_if(auth()->user()->role === 'peminjam' && $peminjaman->peminjam_id !== auth()->id(), 403);

        return view('peminjaman.show', [
            'peminjaman' => $peminjaman->load(['peminjam:id,name', 'petugas:id,name', 'detailPeminjaman.alat:id,kode,nama']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman): View
    {
        return view('peminjaman.edit', ['peminjaman' => $peminjaman]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['diajukan', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan', 'dibatalkan'])],
            'catatan_petugas' => ['nullable', 'string'],
        ]);
        $oldData = $peminjaman->toArray();

        $peminjaman->update($data);
        $this->log('update', $peminjaman, $oldData, $peminjaman->fresh()->toArray());

        return redirect()->route('peminjaman.show', $peminjaman)->with('status', 'Peminjaman diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman): RedirectResponse
    {
        $oldData = $peminjaman->load('detailPeminjaman')->toArray();
        $peminjaman->delete();
        $this->log('delete', $peminjaman, $oldData);

        return redirect()->route('peminjaman.index')->with('status', 'Peminjaman dihapus.');
    }

    public function approve(Peminjaman $peminjaman): RedirectResponse
    {
        abort_unless($peminjaman->status === 'diajukan', 422, 'Hanya pengajuan baru yang bisa disetujui.');

        DB::transaction(function () use ($peminjaman): void {
            $details = $peminjaman->detailPeminjaman()->with('alat')->lockForUpdate()->get();

            foreach ($details as $detail) {
                if ($detail->alat->stok_tersedia < $detail->jumlah) {
                    abort(422, 'Stok alat '.$detail->alat->nama.' tidak mencukupi.');
                }

                $detail->alat->decrement('stok_tersedia', $detail->jumlah);
            }

            $peminjaman->update([
                'petugas_id' => auth()->id(),
                'tanggal_disetujui' => today(),
                'tanggal_pinjam' => today(),
                'status' => 'dipinjam',
            ]);
        });

        $this->log('approve', $peminjaman, null, $peminjaman->fresh()->toArray());

        return back()->with('status', 'Peminjaman disetujui dan stok alat dikurangi.');
    }

    public function reject(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $data = $request->validate([
            'catatan_petugas' => ['nullable', 'string'],
        ]);

        abort_unless($peminjaman->status === 'diajukan', 422, 'Hanya pengajuan baru yang bisa ditolak.');

        $peminjaman->update([
            'petugas_id' => auth()->id(),
            'status' => 'ditolak',
            'catatan_petugas' => $data['catatan_petugas'] ?? null,
        ]);
        $this->log('reject', $peminjaman, null, $peminjaman->fresh()->toArray());

        return back()->with('status', 'Peminjaman ditolak.');
    }

    private function log(string $aksi, Peminjaman $peminjaman, ?array $dataLama = null, ?array $dataBaru = null): void
    {
        LogAktivitas::query()->create([
            'aksi' => $aksi,
            'modul' => 'peminjaman',
            'subjek_type' => Peminjaman::class,
            'subjek_id' => $peminjaman->id,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
