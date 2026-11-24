<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM Laundry</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F6F5F2] flex flex-col items-center justify-center p-4 antialiased font-sans text-stone-900 selection:bg-emerald-900 selection:text-white">
    <div class="w-full max-w-sm">
        {{-- Card Putih Bersih di Tengah --}}
        <div class="bg-white border border-stone-200 rounded-2xl p-6 sm:p-8 shadow-xs">
            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-xl font-bold tracking-tight text-stone-900">SIM Laundry</h1>
                <p class="text-xs text-stone-500 mt-1">Masuk untuk mengelola operasional laundry</p>
            </div>

            {{-- Pesan Notifikasi Sukses / Error --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-xl">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Form Login Murni --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-medium text-stone-700 mb-1.5">Email</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="nama@laundry.com"
                           class="w-full px-3 py-2 text-xs rounded-lg border border-stone-300 text-stone-900 placeholder:text-stone-400 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900 transition-colors">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-medium text-stone-700">Password</label>
                    </div>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required 
                           placeholder="••••••••"
                           class="w-full px-3 py-2 text-xs rounded-lg border border-stone-300 text-stone-900 placeholder:text-stone-400 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900 transition-colors">
                </div>

                <div class="flex items-center text-xs text-stone-600">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-stone-300 text-emerald-900 focus:ring-emerald-900">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full py-2.5 px-4 bg-emerald-900 hover:bg-emerald-950 text-white font-medium rounded-lg text-xs transition-colors cursor-pointer">
                    Masuk
                </button>

                <div class="text-center pt-2">
                    <a href="{{ route('register') }}" class="text-xs text-stone-500 hover:text-stone-900 transition-colors">
                        Belum punya akun? <span class="font-semibold underline">Daftar</span>
                    </a>
                </div>
            </form>
        </div>

        {{-- Footer Sederhana --}}
        <p class="text-center text-xs text-stone-400 mt-5">
            &copy; 2026 SIM Laundry
        </p>
    </div>
</body>
</html>
