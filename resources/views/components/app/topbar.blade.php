@props([
    'title' => 'Aplikasi Peminjaman Alat',
    'currentRole' => 'peminjam',
    'searchableMenus' => collect(),
])

<header class="sticky top-0 z-20 border-b border-white/70 bg-white/85 px-4 py-4 shadow-sm backdrop-blur-xl sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex min-w-0 items-center gap-3">
            <button id="sidebarToggle" type="button" class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-950 text-white shadow-lg lg:hidden" aria-label="Buka menu">
                <span class="grid gap-1.5">
                    <span class="block h-0.5 w-5 rounded-full bg-white"></span>
                    <span class="block h-0.5 w-5 rounded-full bg-white"></span>
                    <span class="block h-0.5 w-5 rounded-full bg-white"></span>
                </span>
            </button>

            <div class="min-w-0">
                <div class="mb-1 flex items-center gap-2">
                    <span class="hidden rounded-full bg-teal-100 px-2.5 py-1 text-[11px] font-black text-teal-700 sm:inline-flex">Web App</span>
                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-black capitalize text-amber-700">{{ $currentRole }}</span>
                </div>
                <h1 class="truncate text-xl font-black text-slate-950 sm:text-2xl">{{ $title }}</h1>
            </div>
        </div>

        <div class="flex min-w-0 flex-1 justify-end gap-3">
            <div class="hidden w-full max-w-xs items-center rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm md:flex">
                <span class="text-xs font-black uppercase text-slate-400">Cari</span>
                <input id="quickSearch" class="border-0 p-0 pl-2 text-sm outline-none" type="search" placeholder="menu atau data..." autocomplete="off" data-menu-map="{{ $searchableMenus->toJson() }}">
            </div>

            @if ($currentRole === 'peminjam')
                <a class="hidden items-center rounded-2xl bg-slate-950 px-4 py-2.5 text-sm font-black text-white shadow-lg shadow-slate-300 sm:inline-flex" href="{{ route('peminjaman.create') }}">Ajukan</a>
            @endif

            <span id="clock" class="rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-black text-slate-600 shadow-sm">{{ now()->format('H:i') }}</span>
        </div>
    </div>
</header>
