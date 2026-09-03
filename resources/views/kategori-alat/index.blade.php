@extends('layouts.app', ['title' => 'Kategori Alat'])

@section('content')
    <p><x-ui.action-button variant="primary" :href="route('kategori-alat.create')">Tambah kategori</x-ui.action-button></p>
    <x-ui.table-wrap>
        <table>
            <thead><tr><th>Kode</th><th>Nama</th><th>Aktif</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach ($kategoriAlat as $item)
                    <tr>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->aktif ? 'Ya' : 'Tidak' }}</td>
                        <td>
                            <div class="actions flex flex-wrap gap-2">
                                <x-ui.action-button variant="detail" :href="route('kategori-alat.show', $item)">Detail</x-ui.action-button>
                                <x-ui.action-button variant="edit" :href="route('kategori-alat.edit', $item)">Edit</x-ui.action-button>
                                <form class="inline" method="POST" action="{{ route('kategori-alat.destroy', $item) }}">
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
    {{ $kategoriAlat->links() }}
@endsection
