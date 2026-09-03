@extends('layouts.app', ['title' => 'Data Alat'])

@section('content')
    <p><x-ui.action-button variant="primary" :href="route('alat.create')">Tambah alat</x-ui.action-button></p>
    <x-ui.table-wrap>
        <table>
            <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Stok</th><th>Kondisi</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach ($alat as $item)
                    <tr>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kategoriAlat?->nama ?? '-' }}</td>
                        <td>{{ $item->stok_tersedia }} / {{ $item->stok_total }}</td>
                        <td>{{ str_replace('_', ' ', $item->kondisi) }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            <div class="actions flex flex-wrap gap-2">
                                <x-ui.action-button variant="detail" :href="route('alat.show', $item)">Detail</x-ui.action-button>
                                <x-ui.action-button variant="edit" :href="route('alat.edit', $item)">Edit</x-ui.action-button>
                                <form class="inline" method="POST" action="{{ route('alat.destroy', $item) }}">
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
    {{ $alat->links() }}
@endsection
