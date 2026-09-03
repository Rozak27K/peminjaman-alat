@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <section class="mb-6 grid gap-5 xl:grid-cols-[1.35fr_.65fr]">
        <div class="overflow-hidden rounded-3xl bg-slate-950 text-white shadow-2xl shadow-slate-300">
            <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-[1fr_220px]">
                <div>
                    <div class="mb-3 inline-flex rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-black text-teal-100">Inventory Control</div>
                    <h2 class="text-3xl font-black leading-tight sm:text-4xl">Kelola peminjaman alat dengan alur yang jelas.</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">Pantau stok, proses pengajuan, setujui peminjaman, dan catat pengembalian dalam satu tampilan kerja.</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        @if ($role === 'peminjam')
                            <x-ui.action-button variant="primary" :href="route('peminjaman.create')">Ajukan peminjaman</x-ui.action-button>
                        @endif
                        <a class="inline-flex items-center rounded-xl border border-white/15 px-4 py-2.5 text-sm font-black text-white hover:bg-white/10" href="{{ route('peminjaman.index') }}">Lihat transaksi</a>
                    </div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                    <div class="text-xs font-black uppercase text-slate-400">Status Sistem</div>
                    <div class="mt-4 grid gap-3">
                        <div class="rounded-xl bg-white p-3 text-slate-950">
                            <div class="text-xs font-bold text-slate-500">Alat tersedia</div>
                            <div class="text-2xl font-black">{{ $alat->sum('stok_tersedia') }}</div>
                        </div>
                        <div class="rounded-xl bg-amber-300 p-3 text-slate-950">
                            <div class="text-xs font-bold text-amber-900">Role aktif</div>
                            <div class="text-2xl font-black capitalize">{{ $role }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-3">
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-xl shadow-slate-200/70">
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-teal-600 font-black text-white">1</div>
                <div>
                    <strong class="text-slate-950">Peminjam mengajukan</strong>
                    <div class="text-sm text-slate-500">Pilih alat, jumlah, dan tanggal kembali.</div>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-xl shadow-slate-200/70">
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-amber-500 font-black text-white">2</div>
                <div>
                    <strong class="text-slate-950">Petugas menyetujui</strong>
                    <div class="text-sm text-slate-500">Stok otomatis berkurang.</div>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-xl shadow-slate-200/70">
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-slate-900 font-black text-white">3</div>
                <div>
                    <strong class="text-slate-950">Alat dikembalikan</strong>
                    <div class="text-sm text-slate-500">Stok masuk kembali.</div>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-grid" aria-label="Ringkasan data">
        @foreach ($stats as $label => $value)
            <x-ui.card>
                <div class="muted">{{ ucfirst($label) }}</div>
                <div class="number">{{ $value }}</div>
            </x-ui.card>
        @endforeach
    </section>

    <section class="section">
        <h2>Daftar Alat</h2>
        <x-ui.table-wrap>
            @if ($alat->isEmpty())
                <div class="empty">Belum ada data alat.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alat as $item)
                            <tr>
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->kategoriAlat?->nama ?? '-' }}</td>
                                <td>{{ $item->stok_tersedia }} / {{ $item->stok_total }}</td>
                                <td>{{ str_replace('_', ' ', $item->kondisi) }}</td>
                                <td><span class="status">{{ $item->status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </x-ui.table-wrap>
    </section>

    <section class="section">
        <h2>Peminjaman Terbaru</h2>
        <x-ui.table-wrap>
            @if ($peminjaman->isEmpty())
                <div class="empty">Belum ada data peminjaman.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Peminjam</th>
                            <th>Petugas</th>
                            <th>Pengajuan</th>
                            <th>Rencana Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($peminjaman as $item)
                            <tr>
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->peminjam?->name ?? '-' }}</td>
                                <td>{{ $item->petugas?->name ?? '-' }}</td>
                                <td>{{ $item->tanggal_pengajuan?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $item->tanggal_rencana_kembali?->format('d/m/Y') ?? '-' }}</td>
                                <td><span class="status">{{ $item->status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </x-ui.table-wrap>
    </section>
@endsection
