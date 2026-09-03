@extends('layouts.app', ['title' => 'Detail Peminjaman'])

@section('content')
    <x-ui.card class="section">
        <p><strong>Kode:</strong> {{ $peminjaman->kode }}</p>
        <p><strong>Peminjam:</strong> {{ $peminjaman->peminjam?->name ?? '-' }}</p>
        <p><strong>Petugas:</strong> {{ $peminjaman->petugas?->name ?? '-' }}</p>
        <p><strong>Status:</strong> {{ $peminjaman->status }}</p>
        <p><strong>Keperluan:</strong> {{ $peminjaman->keperluan }}</p>
    </x-ui.card>

    <x-ui.table-wrap class="section">
        <table>
            <thead><tr><th>Alat</th><th>Jumlah</th><th>Kondisi Pinjam</th><th>Kondisi Kembali</th></tr></thead>
            <tbody>
                @foreach ($peminjaman->detailPeminjaman as $detail)
                    <tr>
                        <td>{{ $detail->alat?->kode }} - {{ $detail->alat?->nama }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>{{ str_replace('_', ' ', $detail->kondisi_saat_pinjam) }}</td>
                        <td>{{ $detail->kondisi_saat_kembali ? str_replace('_', ' ', $detail->kondisi_saat_kembali) : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-ui.table-wrap>

    @if (auth()->user()->role === 'petugas' && $peminjaman->status === 'diajukan')
        <div class="actions flex flex-wrap gap-2">
            <form class="inline" method="POST" action="{{ route('peminjaman.approve', $peminjaman) }}">
                @csrf @method('PATCH')
                <x-ui.action-button variant="accept" type="submit">Setujui</x-ui.action-button>
            </form>
            <form class="inline" method="POST" action="{{ route('peminjaman.reject', $peminjaman) }}">
                @csrf @method('PATCH')
                <x-ui.action-button variant="reject" type="submit">Tolak</x-ui.action-button>
            </form>
        </div>
    @endif
@endsection
