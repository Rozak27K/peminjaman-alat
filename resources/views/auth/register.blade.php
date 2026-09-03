<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-app.head title="Register - Peminjaman Alat" />
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <main class="grid min-h-screen place-items-center px-4 py-8">
        <x-auth.card class="max-w-lg" title="Register Peminjam" description="Akun baru otomatis dibuat sebagai peminjam.">
            <form method="POST" action="{{ route('register.store') }}" class="grid gap-4 p-6">
                @csrf

                @if ($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="grid gap-2 text-sm font-bold text-slate-700">
                        Nama
                        <input name="name" value="{{ old('name') }}" required class="rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100">
                    </label>

                    <label class="grid gap-2 text-sm font-bold text-slate-700">
                        Email
                        <input type="email" name="email" value="{{ old('email') }}" required class="rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100">
                    </label>
                </div>

                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Telepon
                    <input name="telepon" value="{{ old('telepon') }}" class="rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100">
                </label>

                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Alamat
                    <textarea name="alamat" class="min-h-24 rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100">{{ old('alamat') }}</textarea>
                </label>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="grid gap-2 text-sm font-bold text-slate-700">
                        Password
                        <input type="password" name="password" required class="rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100">
                    </label>

                    <label class="grid gap-2 text-sm font-bold text-slate-700">
                        Konfirmasi
                        <input type="password" name="password_confirmation" required class="rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100">
                    </label>
                </div>

                <button type="submit" class="rounded-xl bg-slate-950 px-4 py-3 font-black text-white shadow-lg shadow-slate-300 transition hover:bg-teal-700">Daftar</button>

                <div class="text-center text-sm text-slate-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-black text-teal-700 hover:text-teal-900">Login</a>
                </div>
            </form>
        </x-auth.card>
    </main>
</body>
</html>
