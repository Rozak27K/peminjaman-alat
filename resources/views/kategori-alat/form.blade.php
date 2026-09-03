@extends('layouts.app', ['title' => $kategoriAlat->exists ? 'Edit Kategori' : 'Tambah Kategori'])

@section('content')
    <form method="POST" action="{{ $kategoriAlat->exists ? route('kategori-alat.update', $kategoriAlat) : route('kategori-alat.store') }}" class="card">
        @csrf
        @if ($kategoriAlat->exists)
            @method('PUT')
        @endif
        <div class="form-grid">
            <label>Kode <input name="kode" value="{{ old('kode', $kategoriAlat->kode) }}" required></label>
            <label>Nama <input name="nama" value="{{ old('nama', $kategoriAlat->nama) }}" required></label>
            <label>Aktif
                <select name="aktif">
                    <option value="1" @selected(old('aktif', $kategoriAlat->aktif ?? true))>Ya</option>
                    <option value="0" @selected(! old('aktif', $kategoriAlat->aktif ?? true))>Tidak</option>
                </select>
            </label>
        </div>
        <p><label>Deskripsi <textarea name="deskripsi">{{ old('deskripsi', $kategoriAlat->deskripsi) }}</textarea></label></p>
        <x-ui.action-button variant="primary" type="submit">Simpan</x-ui.action-button>
    </form>
@endsection
