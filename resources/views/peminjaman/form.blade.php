@extends('layouts.app', ['title' => 'Ajukan Peminjaman'])

@section('content')
    <form method="POST" action="{{ route('peminjaman.store') }}" class="card">
        @csrf
        <div class="form-grid">
            @if (auth()->user()->role === 'peminjam')
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs font-bold text-slate-500">Peminjam</div>
                    <div class="mt-1 font-black text-slate-950">{{ auth()->user()->name }}</div>
                </div>
            @else
                <label>Peminjam
                    <select name="peminjam_id" required>
                        @foreach ($peminjam as $user)
                            <option value="{{ $user->id }}" @selected(old('peminjam_id') == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </label>
            @endif
            <label>Alat
                <select name="alat_id" required>
                    @foreach ($alat as $item)
                        <option value="{{ $item->id }}" @selected(old('alat_id') == $item->id)>{{ $item->kode }} - {{ $item->nama }} (stok {{ $item->stok_tersedia }})</option>
                    @endforeach
                </select>
            </label>
            <label>Jumlah <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required></label>
            <label>Rencana Kembali <input type="date" name="tanggal_rencana_kembali" value="{{ old('tanggal_rencana_kembali', now()->addWeek()->toDateString()) }}" required></label>
        </div>
        <p><label>Keperluan <textarea name="keperluan" required>{{ old('keperluan') }}</textarea></label></p>
        <p><label>Catatan <textarea name="catatan_peminjam">{{ old('catatan_peminjam') }}</textarea></label></p>
        <x-ui.action-button variant="primary" type="submit">Simpan Pengajuan</x-ui.action-button>
    </form>
@endsection
