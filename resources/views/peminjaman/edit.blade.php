@extends('layouts.app', ['title' => 'Edit Status Peminjaman'])

@section('content')
    <form method="POST" action="{{ route('peminjaman.update', $peminjaman) }}" class="card">
        @csrf @method('PUT')
        <div class="form-grid">
            <label>Status
                <select name="status">
                    @foreach (['diajukan', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan', 'dibatalkan'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $peminjaman->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <p><label>Catatan Petugas <textarea name="catatan_petugas">{{ old('catatan_petugas', $peminjaman->catatan_petugas) }}</textarea></label></p>
        <x-ui.action-button variant="primary" type="submit">Simpan</x-ui.action-button>
    </form>
@endsection
