<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sistem Laundry') - SIM Laundry</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-stone-900 antialiased font-sans flex flex-col lg:flex-row selection:bg-emerald-900 selection:text-white">

    {{-- Mobile Top Header Bar (< lg) --}}
    <header class="lg:hidden bg-[#F6F5F2] border-b border-stone-200 sticky top-0 z-30 px-4 py-3 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="font-bold text-xl text-stone-900 tracking-tight">
            SIM Laundry
        </a>
        <button type="button" 
                id="openSidebarBtn" 
                class="p-2 rounded-lg text-stone-700 hover:bg-stone-200/70 transition-colors cursor-pointer"
                aria-label="Buka menu navigasi">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </header>

    {{-- Backdrop Overlay for Mobile Drawer --}}
    <div id="sidebarBackdrop" class="fixed inset-0 bg-stone-900/30 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

    {{-- Left Sidebar (Warm Stone Sidebar) --}}
    <aside id="appSidebar" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-[#F6F5F2] border-r border-stone-200 transform -translate-x-full transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:h-screen lg:shrink-0 lg:sticky lg:top-0 flex flex-col justify-between">
        
        {{-- Sidebar Top: Brand Title & Close Button for Mobile --}}
        <div>
            <div class="p-6 pb-4 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="font-bold text-xl text-stone-900 tracking-tight block">
                    SIM Laundry
                </a>
                <button type="button" 
                        id="closeSidebarBtn" 
                        class="lg:hidden p-1.5 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-200 transition-colors cursor-pointer"
                        aria-label="Tutup menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Sidebar Middle: HANYA 2 Menu Navigasi Utama --}}
            <nav class="px-3 py-2 space-y-1">
                {{-- 1. Data Layanan --}}
                <a href="{{ route('layanan.index') }}" 
                   class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs transition-colors {{ request()->routeIs('layanan.*') ? 'bg-white text-stone-900 font-semibold border border-stone-200/80 shadow-xs' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-200/50 font-medium' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('layanan.*') ? 'text-stone-900' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span>1. Data Layanan</span>
                </a>

                {{-- 2. Rekap Transaksi Laundry --}}
                <a href="{{ route('transaksi.index') }}" 
                   class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs transition-colors {{ request()->routeIs('transaksi.*') ? 'bg-white text-stone-900 font-semibold border border-stone-200/80 shadow-xs' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-200/50 font-medium' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('transaksi.*') ? 'text-stone-900' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>2. Rekap Transaksi Laundry</span>
                </a>
            </nav>
        </div>

        {{-- Sidebar Bottom: User Info, Role Badge, Logout --}}
        @auth
            <div class="p-4 border-t border-stone-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="min-w-0 pr-2">
                        <div class="text-xs font-semibold text-stone-900 truncate">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-stone-500 truncate">{{ auth()->user()->email }}</div>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-stone-900 text-white">
                            Admin
                        </span>
                    @else
                        <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-medium uppercase bg-white text-stone-700 border border-stone-200">
                            Kasir
                        </span>
                    @endif
                </div>

                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" 
                            class="w-full text-center text-xs font-medium text-stone-600 hover:text-stone-900 hover:bg-white hover:border-stone-200 py-1.5 rounded-lg border border-transparent transition-colors cursor-pointer">
                        Keluar
                    </button>
                </form>
            </div>
        @endauth
    </aside>

    {{-- Main Workspace (Pure White Canvas) --}}
    <div class="flex-1 min-w-0 bg-white flex flex-col min-h-screen">
        <main class="max-w-5xl w-full mx-auto p-4 sm:p-6 lg:p-8 grow">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50/70 border border-emerald-200 text-emerald-900 rounded-xl flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-600 shrink-0"></span>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 cursor-pointer text-sm leading-none">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50/70 border border-rose-200 text-rose-900 rounded-xl flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-rose-600 shrink-0"></span>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 cursor-pointer text-sm leading-none">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50/70 border border-rose-200 text-rose-900 rounded-xl text-xs space-y-1">
                    <div class="font-semibold">Terjadi kesalahan pada input:</div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-800">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer Sederhana --}}
        <footer class="border-t border-stone-100 py-4 text-center text-xs text-stone-400 mt-auto">
            <div class="max-w-5xl mx-auto px-4">
                <p>&copy; 2026 SIM Laundry</p>
            </div>
        </footer>
    </div>

    {{-- Responsive Sidebar Drawer Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('appSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const openBtn = document.getElementById('openSidebarBtn');
            const closeBtn = document.getElementById('closeSidebarBtn');

            function openSidebar() {
                if (sidebar && backdrop) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                }
            }

            function closeSidebar() {
                if (sidebar && backdrop) {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            }

            openBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            backdrop?.addEventListener('click', closeSidebar);
        });
    </script>
</body>
</html>
