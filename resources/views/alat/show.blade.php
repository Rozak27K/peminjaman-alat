@extends('layouts.app', ['title' => 'Detail Alat'])

@section('content')
    <x-ui.card>
        <p><strong>Kode:</strong> {{ $alat->kode }}</p>
        <p><strong>Nama:</strong> {{ $alat->nama }}</p>
        <p><strong>Kategori:</strong> {{ $alat->kategoriAlat?->nama ?? '-' }}</p>
        <p><strong>Stok:</strong> {{ $alat->stok_tersedia }} / {{ $alat->stok_total }}</p>
        <p><strong>Status:</strong> {{ $alat->status }}</p>
        <p><strong>Deskripsi:</strong> {{ $alat->deskripsi ?? '-' }}</p>
    </x-ui.card>
@endsection
