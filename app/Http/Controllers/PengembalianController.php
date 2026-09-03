<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengembalianController extends Controller
{
    public function index(): View
    {
        return view('pengembalian.index', [
            'pengembalian' => Pengembalian::query()
                ->with(['peminjaman.peminjam:id,name', 'petugas:id,name'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('pengembalian.form', [
            'pengembalian' => new Pengembalian,
            'peminjaman' => Peminjaman::query()
                ->with('peminjam:id,name')
                ->where('status', 'dipinjam')
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'peminjaman_id' => ['required', 'exists:peminjaman,id'],
            'tanggal_kembali' => ['required', 'date'],
            'denda' => ['required', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);

        $pengembalian = DB::transaction(function () use ($data): Pengembalian {
            $peminjaman = Peminjaman::query()->whereKey($data['peminjaman_id'])->lockForUpdate()->firstOrFail();
            abort_unless($peminjaman->status === 'dipinjam', 422, 'Peminjaman ini belum berstatus dipinjam.');

            $pengembalian = Pengembalian::query()->create([
                'kode' => 'PGB-'.now()->format('YmdHis'),
                'peminjaman_id' => $peminjaman->id,
                'petugas_id' => User::query()->where('role', 'petugas')->value('id'),
                'tanggal_kembali' => $data['tanggal_kembali'],
                'status' => 'selesai',
                'denda' => $data['denda'],
                'catatan' => $data['catatan'] ?? null,
            ]);

            $details = DetailPeminjaman::query()->with('alat')->whereBelongsTo($peminjaman)->get();

            foreach ($details as $detail) {
                $detail->alat->increment('stok_tersedia', $detail->jumlah);
                $detail->update(['kondisi_saat_kembali' => $detail->kondisi_saat_pinjam]);
            }

            $peminjaman->update(['status' => 'dikembalikan']);

            return $pengembalian;
        });

        $this->log('create', $pengembalian, null, $pengembalian->toArray());

        return redirect()->route('pengembalian.show', $pengembalian)->with('status', 'Pengembalian diproses.');
    }

    public function show(Pengembalian $pengembalian): View
    {
        return view('pengembalian.show', [
            'pengembalian' => $pengembalian->load(['peminjaman.peminjam:id,name', 'petugas:id,name']),
        ]);
    }

    public function edit(Pengembalian $pengembalian): View
    {
        return view('pengembalian.form', [
            'pengembalian' => $pengembalian,
            'peminjaman' => Peminjaman::query()
                ->with('peminjam:id,name')
                ->whereKey($pengembalian->peminjaman_id)
                ->get(),
        ]);
    }

    public function update(Request $request, Pengembalian $pengembalian): RedirectResponse
    {
        $data = $request->validate([
            'tanggal_kembali' => ['required', 'date'],
            'denda' => ['required', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);
        $oldData = $pengembalian->toArray();

        $pengembalian->update($data);
        $this->log('update', $pengembalian, $oldData, $pengembalian->fresh()->toArray());

        return redirect()->route('pengembalian.show', $pengembalian)->with('status', 'Pengembalian diperbarui.');
    }

    public function destroy(Pengembalian $pengembalian): RedirectResponse
    {
        $oldData = $pengembalian->toArray();
        $pengembalian->delete();
        $this->log('delete', $pengembalian, $oldData);

        return redirect()->route('pengembalian.index')->with('status', 'Pengembalian dihapus.');
    }

    private function log(string $aksi, Pengembalian $pengembalian, ?array $dataLama = null, ?array $dataBaru = null): void
    {
        LogAktivitas::query()->create([
            'aksi' => $aksi,
            'modul' => 'pengembalian',
            'subjek_type' => Pengembalian::class,
            'subjek_id' => $pengembalian->id,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
