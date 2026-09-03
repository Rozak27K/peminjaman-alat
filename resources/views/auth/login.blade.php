<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-app.head title="Login - Peminjaman Alat" />
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <main class="grid min-h-screen place-items-center px-4 py-8">
        <x-auth.card class="max-w-md" title="Login Aplikasi" description="Masuk untuk mengelola peminjaman alat sesuai role akun.">
            <form method="POST" action="{{ route('login.store') }}" class="grid gap-4 p-6">
                @csrf

                @if ($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100">
                </label>

                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Password
                    <input type="password" name="password" required class="rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100">
                </label>

                <label class="flex items-center gap-2 text-sm font-semibold text-slate-600">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-teal-600">
                    Ingat saya
                </label>

                <button type="submit" class="rounded-xl bg-slate-950 px-4 py-3 font-black text-white shadow-lg shadow-slate-300 transition hover:bg-teal-700">Masuk</button>

                <div class="text-center text-sm text-slate-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-black text-teal-700 hover:text-teal-900">Register</a>
                </div>

                <div class="rounded-xl bg-slate-50 p-4 text-xs leading-5 text-slate-500">
                    Akun awal: <strong>admin@example.com</strong>, <strong>petugas@example.com</strong>, atau <strong>peminjam@example.com</strong>. Password: <strong>password</strong>.
                </div>
            </form>
        </x-auth.card>
    </main>
</body>
</html>
