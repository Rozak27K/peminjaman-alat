@extends('layouts.app', ['title' => 'Data Pengembalian'])

@section('content')
    <p><x-ui.action-button variant="primary" :href="route('pengembalian.create')">Proses pengembalian</x-ui.action-button></p>
    <x-ui.table-wrap>
        <table>
            <thead><tr><th>Kode</th><th>Peminjaman</th><th>Peminjam</th><th>Petugas</th><th>Tanggal</th><th>Denda</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach ($pengembalian as $item)
                    <tr>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->peminjaman?->kode }}</td>
                        <td>{{ $item->peminjaman?->peminjam?->name ?? '-' }}</td>
                        <td>{{ $item->petugas?->name ?? '-' }}</td>
                        <td>{{ $item->tanggal_kembali?->format('d/m/Y') }}</td>
                        <td>{{ number_format((float) $item->denda, 0, ',', '.') }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            <div class="actions flex flex-wrap gap-2">
                                <x-ui.action-button variant="detail" :href="route('pengembalian.show', $item)">Detail</x-ui.action-button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-ui.table-wrap>
    {{ $pengembalian->links() }}
@endsection
