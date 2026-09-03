<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-app.head :title="$title ?? 'Peminjaman Alat'" />
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    @php
        $currentUser = auth()->user();
        $currentRole = $currentUser?->role ?? 'peminjam';
        $navGroups = [
            'Utama' => [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'match' => 'dashboard', 'icon' => '01', 'description' => 'Ringkasan sistem', 'roles' => ['admin', 'petugas', 'peminjam']],
                ['label' => 'Peminjaman', 'route' => 'peminjaman.index', 'match' => 'peminjaman.*', 'icon' => '02', 'description' => 'Pengajuan alat', 'roles' => ['admin', 'petugas', 'peminjam']],
                ['label' => 'Pengembalian', 'route' => 'pengembalian.index', 'match' => 'pengembalian.*', 'icon' => '03', 'description' => 'Proses kembali', 'roles' => ['admin', 'petugas', 'peminjam']],
            ],
            'Master Data' => [
                ['label' => 'Data Alat', 'route' => 'alat.index', 'match' => 'alat.*', 'icon' => '04', 'description' => 'Inventaris', 'roles' => ['admin']],
                ['label' => 'Kategori', 'route' => 'kategori-alat.index', 'match' => 'kategori-alat.*', 'icon' => '05', 'description' => 'Jenis alat', 'roles' => ['admin']],
                ['label' => 'Pengguna', 'route' => 'users.index', 'match' => 'users.*', 'icon' => '06', 'description' => 'Akun dan role', 'roles' => ['admin']],
            ],
            'Monitoring' => [
                ['label' => 'Laporan', 'route' => 'laporan.index', 'match' => 'laporan.*', 'icon' => '07', 'description' => 'Rekap data', 'roles' => ['petugas']],
                ['label' => 'Log Aktivitas', 'route' => 'log-aktivitas.index', 'match' => 'log-aktivitas.*', 'icon' => '08', 'description' => 'Jejak aksi', 'roles' => ['admin']],
            ],
        ];

        $searchableMenus = collect($navGroups)
            ->flatten(1)
            ->filter(fn (array $item): bool => in_array($currentRole, $item['roles'], true))
            ->mapWithKeys(fn (array $item): array => [strtolower($item['label']) => route($item['route'])]);
    @endphp

    <div class="fixed inset-0 -z-10 bg-[linear-gradient(135deg,#f8fafc,#eef2f7_45%,#e0f2f1)]"></div>
    <div id="overlay" class="fixed inset-0 z-30 hidden bg-slate-950/50 backdrop-blur-sm lg:hidden"></div>

    <div class="grid min-h-screen lg:grid-cols-[304px_1fr]">
        <x-app.sidebar :current-user="$currentUser" :current-role="$currentRole" :nav-groups="$navGroups" />

        <div class="flex min-w-0 flex-col">
            <x-app.topbar
                :title="$title ?? 'Aplikasi Peminjaman Alat'"
                :current-role="$currentRole"
                :searchable-menus="$searchableMenus"
            />

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @if (session('status'))
                    <div class="notice">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="errors">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </main>

            <x-app.footer />
        </div>
    </div>
</body>
</html>
