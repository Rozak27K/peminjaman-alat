@extends('layouts.app', ['title' => 'Data User'])

@section('content')
    <p><x-ui.action-button variant="primary" :href="route('users.create')">Tambah user</x-ui.action-button></p>
    <x-ui.table-wrap>
        <table>
            <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Telepon</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->telepon ?? '-' }}</td>
                        <td>
                            <div class="actions flex flex-wrap gap-2">
                                <x-ui.action-button variant="detail" :href="route('users.show', $user)">Detail</x-ui.action-button>
                                <x-ui.action-button variant="edit" :href="route('users.edit', $user)">Edit</x-ui.action-button>
                                <form class="inline" method="POST" action="{{ route('users.destroy', $user) }}">
                                    @csrf @method('DELETE')
                                    <x-ui.action-button variant="delete" type="submit">Hapus</x-ui.action-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-ui.table-wrap>
    {{ $users->links() }}
@endsection
