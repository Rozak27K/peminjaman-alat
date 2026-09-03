@props([
    'title',
    'description',
])

<section {{ $attributes->merge(['class' => 'w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-300/60']) }}>
    <div class="bg-slate-950 p-6 text-white">
        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br from-teal-300 via-emerald-400 to-amber-300 text-lg font-black text-slate-950">PA</div>
        <h1 class="mt-5 text-2xl font-black">{{ $title }}</h1>
        <p class="mt-2 text-sm leading-6 text-slate-300">{{ $description }}</p>
    </div>

    {{ $slot }}
</section>
