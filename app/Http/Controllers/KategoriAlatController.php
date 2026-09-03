<?php

namespace App\Http\Controllers;

use App\Models\KategoriAlat;
use App\Models\LogAktivitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KategoriAlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('kategori-alat.index', [
            'kategoriAlat' => KategoriAlat::query()->latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('kategori-alat.form', [
            'kategoriAlat' => new KategoriAlat,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        $kategoriAlat = KategoriAlat::query()->create($data);
        $this->log('create', $kategoriAlat, null, $kategoriAlat->toArray());

        return redirect()->route('kategori-alat.index')->with('status', 'Kategori alat dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriAlat $kategoriAlat): View
    {
        return view('kategori-alat.show', ['kategoriAlat' => $kategoriAlat]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriAlat $kategoriAlat): View
    {
        return view('kategori-alat.form', ['kategoriAlat' => $kategoriAlat]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriAlat $kategoriAlat): RedirectResponse
    {
        $data = $request->validate($this->rules($kategoriAlat));
        $oldData = $kategoriAlat->toArray();

        $kategoriAlat->update($data);
        $this->log('update', $kategoriAlat, $oldData, $kategoriAlat->fresh()->toArray());

        return redirect()->route('kategori-alat.index')->with('status', 'Kategori alat diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriAlat $kategoriAlat): RedirectResponse
    {
        $oldData = $kategoriAlat->toArray();
        $kategoriAlat->delete();
        $this->log('delete', $kategoriAlat, $oldData);

        return redirect()->route('kategori-alat.index')->with('status', 'Kategori alat dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?KategoriAlat $kategoriAlat = null): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'kode' => ['required', 'string', 'max:20', Rule::unique('kategori_alat', 'kode')->ignore($kategoriAlat)],
            'deskripsi' => ['nullable', 'string'],
            'aktif' => ['nullable', 'boolean'],
        ];
    }

    private function log(string $aksi, KategoriAlat $kategoriAlat, ?array $dataLama = null, ?array $dataBaru = null): void
    {
        LogAktivitas::query()->create([
            'aksi' => $aksi,
            'modul' => 'kategori_alat',
            'subjek_type' => KategoriAlat::class,
            'subjek_id' => $kategoriAlat->id,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
