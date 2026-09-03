<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\KategoriAlat;
use App\Models\LogAktivitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('alat.index', [
            'alat' => Alat::query()->with('kategoriAlat:id,nama')->latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('alat.form', [
            'alat' => new Alat,
            'kategoriAlat' => KategoriAlat::query()->where('aktif', true)->orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        $alat = Alat::query()->create($data);
        $this->log('create', $alat, null, $alat->toArray());

        return redirect()->route('alat.index')->with('status', 'Alat dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alat $alat): View
    {
        return view('alat.show', [
            'alat' => $alat->load('kategoriAlat:id,nama'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alat $alat): View
    {
        return view('alat.form', [
            'alat' => $alat,
            'kategoriAlat' => KategoriAlat::query()->where('aktif', true)->orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alat $alat): RedirectResponse
    {
        $data = $request->validate($this->rules($alat));
        $oldData = $alat->toArray();

        $alat->update($data);
        $this->log('update', $alat, $oldData, $alat->fresh()->toArray());

        return redirect()->route('alat.index')->with('status', 'Alat diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alat $alat): RedirectResponse
    {
        $oldData = $alat->toArray();
        $alat->delete();
        $this->log('delete', $alat, $oldData);

        return redirect()->route('alat.index')->with('status', 'Alat dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?Alat $alat = null): array
    {
        return [
            'kategori_alat_id' => ['required', 'exists:kategori_alat,id'],
            'kode' => ['required', 'string', 'max:255', Rule::unique('alat', 'kode')->ignore($alat)],
            'nama' => ['required', 'string', 'max:255'],
            'merk' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'stok_total' => ['required', 'integer', 'min:0'],
            'stok_tersedia' => ['required', 'integer', 'min:0', 'lte:stok_total'],
            'kondisi' => ['required', Rule::in(['baik', 'rusak_ringan', 'rusak_berat'])],
            'status' => ['required', Rule::in(['tersedia', 'dipinjam', 'perbaikan', 'hilang'])],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    private function log(string $aksi, Alat $alat, ?array $dataLama = null, ?array $dataBaru = null): void
    {
        LogAktivitas::query()->create([
            'aksi' => $aksi,
            'modul' => 'alat',
            'subjek_type' => Alat::class,
            'subjek_id' => $alat->id,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
