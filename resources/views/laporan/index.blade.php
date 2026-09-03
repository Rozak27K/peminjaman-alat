@extends('layouts.app', ['title' => 'Laporan'])

@section('content')
    <section class="stats-grid">
        <x-ui.card>
            <div class="muted">Total Denda</div>
            <div class="number">{{ number_format((float) $totalDenda, 0, ',', '.') }}</div>
        </x-ui.card>
        @foreach ($statusPeminjaman as $status)
            <x-ui.card>
                <div class="muted">Peminjaman {{ $status->status }}</div>
                <div class="number">{{ $status->total }}</div>
            </x-ui.card>
        @endforeach
    </section>

    <section class="section">
        <h2>Pengembalian Terbaru</h2>
        <x-ui.table-wrap>
            <table>
                <thead><tr><th>Kode</th><th>Peminjam</th><th>Tanggal</th><th>Denda</th></tr></thead>
                <tbody>
                    @forelse ($pengembalian as $item)
                        <tr>
                            <td>{{ $item->kode }}</td>
                            <td>{{ $item->peminjaman?->peminjam?->name ?? '-' }}</td>
                            <td>{{ $item->tanggal_kembali?->format('d/m/Y') }}</td>
                            <td>{{ number_format((float) $item->denda, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Belum ada data pengembalian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-wrap>
    </section>
@endsection
