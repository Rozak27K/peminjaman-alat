@extends('layouts.app', ['title' => 'Detail User'])

@section('content')
    <x-ui.card>
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ $user->role }}</p>
        <p><strong>Telepon:</strong> {{ $user->telepon ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $user->alamat ?? '-' }}</p>
    </x-ui.card>
@endsection
