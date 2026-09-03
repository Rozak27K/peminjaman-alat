@extends('layouts.app', ['title' => $alat->exists ? 'Edit Alat' : 'Tambah Alat'])

@section('content')
    <form method="POST" action="{{ $alat->exists ? route('alat.update', $alat) : route('alat.store') }}" class="card">
        @csrf
        @if ($alat->exists)
            @method('PUT')
        @endif
        <div class="form-grid">
            <label>Kategori
                <select name="kategori_alat_id" required>
                    @foreach ($kategoriAlat as $kategori)
                        <option value="{{ $kategori->id }}" @selected(old('kategori_alat_id', $alat->kategori_alat_id) == $kategori->id)>{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </label>
            <label>Kode <input name="kode" value="{{ old('kode', $alat->kode) }}" required></label>
            <label>Nama <input name="nama" value="{{ old('nama', $alat->nama) }}" required></label>
            <label>Merk <input name="merk" value="{{ old('merk', $alat->merk) }}"></label>
            <label>Model <input name="model" value="{{ old('model', $alat->model) }}"></label>
            <label>Stok Total <input type="number" name="stok_total" value="{{ old('stok_total', $alat->stok_total ?? 0) }}" min="0" required></label>
            <label>Stok Tersedia <input type="number" name="stok_tersedia" value="{{ old('stok_tersedia', $alat->stok_tersedia ?? 0) }}" min="0" required></label>
            <label>Kondisi
                <select name="kondisi">
                    @foreach (['baik', 'rusak_ringan', 'rusak_berat'] as $kondisi)
                        <option value="{{ $kondisi }}" @selected(old('kondisi', $alat->kondisi ?? 'baik') === $kondisi)>{{ str_replace('_', ' ', $kondisi) }}</option>
                    @endforeach
                </select>
            </label>
            <label>Status
                <select name="status">
                    @foreach (['tersedia', 'dipinjam', 'perbaikan', 'hilang'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $alat->status ?? 'tersedia') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <p><label>Deskripsi <textarea name="deskripsi">{{ old('deskripsi', $alat->deskripsi) }}</textarea></label></p>
        <x-ui.action-button variant="primary" type="submit">Simpan</x-ui.action-button>
    </form>
@endsection
