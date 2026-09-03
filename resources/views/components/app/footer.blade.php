<footer class="border-t border-white/70 bg-white/70 px-4 py-5 text-sm text-slate-500 backdrop-blur sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <span>&copy; {{ date('Y') }} Aplikasi Peminjaman Alat</span>
        <span>Laravel {{ app()->version() }} | {{ config('app.env') }}</span>
    </div>
</footer>
