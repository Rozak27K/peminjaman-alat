@extends('layouts.app', ['title' => 'Detail Pengembalian'])

@section('content')
    <x-ui.card>
        <p><strong>Kode:</strong> {{ $pengembalian->kode }}</p>
        <p><strong>Peminjaman:</strong> {{ $pengembalian->peminjaman?->kode }}</p>
        <p><strong>Peminjam:</strong> {{ $pengembalian->peminjaman?->peminjam?->name ?? '-' }}</p>
        <p><strong>Petugas:</strong> {{ $pengembalian->petugas?->name ?? '-' }}</p>
        <p><strong>Tanggal:</strong> {{ $pengembalian->tanggal_kembali?->format('d/m/Y') }}</p>
        <p><strong>Denda:</strong> {{ number_format((float) $pengembalian->denda, 0, ',', '.') }}</p>
        <p><strong>Catatan:</strong> {{ $pengembalian->catatan ?? '-' }}</p>
    </x-ui.card>
@endsection
