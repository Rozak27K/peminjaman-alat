@props(['currentUser', 'currentRole' => 'peminjam', 'navGroups' => []])

<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-[304px] -translate-x-full flex-col border-r border-white/10 bg-slate-950 text-white shadow-2xl transition-transform duration-200 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
    <div class="border-b border-white/10 px-5 py-5">
        <div class="rounded-2xl border border-white/10 bg-white/[.06] p-4 shadow-2xl shadow-black/20">
            <div class="flex items-center gap-3">
                <div class="grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br from-teal-300 via-emerald-400 to-amber-300 text-lg font-black text-slate-950 shadow-lg shadow-teal-950/40">PA</div>
                <div class="min-w-0">
                    <div class="truncate text-base font-black">Peminjaman Alat</div>
                    <div class="mt-1 text-xs font-semibold text-slate-400">Inventory Management</div>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                <div class="rounded-2xl bg-slate-900 p-3">
                    <div class="text-slate-500">Mode</div>
                    <div class="mt-1 font-black capitalize text-teal-300">{{ $currentRole }}</div>
                </div>
                <div class="rounded-2xl bg-slate-900 p-3">
                    <div class="text-slate-500">Status</div>
                    <div class="mt-1 font-black text-emerald-300">Aktif</div>
                </div>
            </div>
        </div>
    </div>

    <nav class="min-h-0 flex-1 space-y-5 overflow-y-auto px-4 py-5">
        @foreach ($navGroups as $group => $items)
            @php
                $visibleItems = collect($items)->filter(fn (array $item): bool => in_array($currentRole, $item['roles'], true));
            @endphp

            @if ($visibleItems->isNotEmpty())
                <div class="space-y-2">
                    <div class="px-3 text-[11px] font-black uppercase text-slate-500">{{ $group }}</div>

                    @foreach ($visibleItems as $item)
                        @php($isActive = request()->routeIs($item['match']))

                        <a class="{{ $isActive ? 'bg-white text-slate-950 shadow-xl shadow-black/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} group relative flex min-h-[58px] items-center gap-3 rounded-2xl px-3 py-2.5 transition" href="{{ route($item['route']) }}">
                            @if ($isActive)
                                <span class="absolute -left-4 top-1/2 h-8 w-1 -translate-y-1/2 rounded-r-full bg-teal-300"></span>
                            @endif

                            <span class="{{ $isActive ? 'bg-slate-950 text-white ring-4 ring-slate-200' : 'bg-white/10 text-slate-200 group-hover:bg-teal-400 group-hover:text-slate-950' }} grid h-10 w-10 shrink-0 place-items-center rounded-xl text-xs font-black transition">{{ $item['icon'] }}</span>
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-black">{{ $item['label'] }}</span>
                                <span class="{{ $isActive ? 'text-slate-500' : 'text-slate-500 group-hover:text-slate-300' }} block truncate text-xs font-semibold">{{ $item['description'] }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        @endforeach
    </nav>

    <div class="border-t border-white/10 p-4">
        <div class="rounded-2xl border border-white/10 bg-white/[.06] p-4">
            <div class="flex items-center gap-3">
                <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-teal-300 text-sm font-black text-slate-950">{{ strtoupper(substr($currentUser?->name ?? 'U', 0, 1)) }}</div>
                <div class="min-w-0">
                    <div class="truncate text-sm font-black">{{ $currentUser?->name }}</div>
                    <div class="mt-1 text-xs font-semibold capitalize text-slate-400">{{ $currentRole }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full rounded-xl bg-white px-3 py-2 text-sm font-black text-slate-950 transition hover:bg-teal-100">Logout</button>
            </form>
        </div>
    </div>
</aside>
