@extends('layouts.app', ['title' => $pengembalian->exists ? 'Edit Pengembalian' : 'Proses Pengembalian'])

@section('content')
    <form method="POST" action="{{ $pengembalian->exists ? route('pengembalian.update', $pengembalian) : route('pengembalian.store') }}" class="card">
        @csrf
        @if ($pengembalian->exists)
            @method('PUT')
        @endif
        <div class="form-grid">
            @if (! $pengembalian->exists)
                <label>Peminjaman
                    <select name="peminjaman_id" required>
                        @foreach ($peminjaman as $item)
                            <option value="{{ $item->id }}" @selected(old('peminjaman_id') == $item->id)>{{ $item->kode }} - {{ $item->peminjam?->name }}</option>
                        @endforeach
                    </select>
                </label>
            @endif
            <label>Tanggal Kembali <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', $pengembalian->tanggal_kembali?->toDateString() ?? now()->toDateString()) }}" required></label>
            <label>Denda <input type="number" name="denda" value="{{ old('denda', $pengembalian->denda ?? 0) }}" min="0" required></label>
        </div>
        <p><label>Catatan <textarea name="catatan">{{ old('catatan', $pengembalian->catatan) }}</textarea></label></p>
        <x-ui.action-button variant="primary" type="submit">Simpan</x-ui.action-button>
    </form>
@endsection
