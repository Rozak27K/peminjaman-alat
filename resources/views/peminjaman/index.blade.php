@extends('layouts.app', ['title' => 'Data Peminjaman'])

@section('content')
    @if (auth()->user()->role === 'peminjam')
        <p><x-ui.action-button variant="primary" :href="route('peminjaman.create')">Ajukan peminjaman</x-ui.action-button></p>
    @endif
    <x-ui.table-wrap>
        <table>
            <thead><tr><th>Kode</th><th>Peminjam</th><th>Petugas</th><th>Pengajuan</th><th>Rencana Kembali</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach ($peminjaman as $item)
                    <tr>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->peminjam?->name ?? '-' }}</td>
                        <td>{{ $item->petugas?->name ?? '-' }}</td>
                        <td>{{ $item->tanggal_pengajuan?->format('d/m/Y') }}</td>
                        <td>{{ $item->tanggal_rencana_kembali?->format('d/m/Y') }}</td>
                        <td><span class="status">{{ $item->status }}</span></td>
                        <td>
                            <div class="actions flex flex-wrap gap-2">
                                @if (auth()->user()->role === 'petugas' && $item->status === 'diajukan')
                                    <form class="inline" method="POST" action="{{ route('peminjaman.approve', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <x-ui.action-button variant="accept" type="submit">Terima</x-ui.action-button>
                                    </form>
                                    <form class="inline" method="POST" action="{{ route('peminjaman.reject', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <x-ui.action-button variant="reject" type="submit">Tidak</x-ui.action-button>
                                    </form>
                                @endif
                                <x-ui.action-button variant="detail" :href="route('peminjaman.show', $item)">Detail</x-ui.action-button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-ui.table-wrap>
    {{ $peminjaman->links() }}
@endsection
