@extends('layouts.app', ['title' => 'Detail Kategori'])

@section('content')
    <x-ui.card>
        <p><strong>Kode:</strong> {{ $kategoriAlat->kode }}</p>
        <p><strong>Nama:</strong> {{ $kategoriAlat->nama }}</p>
        <p><strong>Aktif:</strong> {{ $kategoriAlat->aktif ? 'Ya' : 'Tidak' }}</p>
        <p><strong>Deskripsi:</strong> {{ $kategoriAlat->deskripsi ?? '-' }}</p>
    </x-ui.card>
@endsection
