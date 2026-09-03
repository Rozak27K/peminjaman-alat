@extends('layouts.app', ['title' => $user->exists ? 'Edit User' : 'Tambah User'])

@section('content')
    <form method="POST" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}" class="card">
        @csrf
        @if ($user->exists)
            @method('PUT')
        @endif
        <div class="form-grid">
            <label>Nama <input name="name" value="{{ old('name', $user->name) }}" required></label>
            <label>Email <input type="email" name="email" value="{{ old('email', $user->email) }}" required></label>
            <label>Password <input type="password" name="password" @required(! $user->exists)></label>
            <label>Role
                <select name="role" required>
                    @foreach (['admin', 'petugas', 'peminjam'] as $role)
                        <option value="{{ $role }}" @selected(old('role', $user->role ?? 'peminjam') === $role)>{{ $role }}</option>
                    @endforeach
                </select>
            </label>
            <label>Telepon <input name="telepon" value="{{ old('telepon', $user->telepon) }}"></label>
        </div>
        <p><label>Alamat <textarea name="alamat">{{ old('alamat', $user->alamat) }}</textarea></label></p>
        <x-ui.action-button variant="primary" type="submit">Simpan</x-ui.action-button>
    </form>
@endsection
