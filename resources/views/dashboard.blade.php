@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header Banner Minimalist --}}
    <div class="bg-white border border-stone-200 rounded-xl p-6 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-bold tracking-tight text-stone-900">Halo, {{ auth()->user()->name }}</h1>
                @if(auth()->user()->isAdmin())
                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-stone-900 text-white">
                        Admin
                    </span>
                @else
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium uppercase bg-white text-stone-700 border border-stone-200">
                        Kasir
                    </span>
                @endif
            </div>
            <p class="text-xs text-stone-500 mt-1">
                Sistem Informasi Manajemen Laundry &mdash; Kelola layanan dan transaksi secara terpusat.
            </p>
        </div>

        <div>
            <a href="{{ route('transaksi.create') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-900 hover:bg-emerald-950 text-white text-xs font-medium rounded-lg transition-colors cursor-pointer">
                <span>+ Catat Transaksi</span>
            </a>
        </div>
    </div>

    {{-- Stat Strip 4 Ringkasan Angka --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-stone-200 rounded-xl p-5 shadow-xs">
            <span class="text-xs font-medium text-stone-500">Total Pendapatan</span>
            <div class="text-2xl font-bold tracking-tight text-stone-900 mt-2">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-stone-400 mt-1">Semua order tercatat</p>
        </div>

        <div class="bg-white border border-stone-200 rounded-xl p-5 shadow-xs">
            <span class="text-xs font-medium text-stone-500">Transaksi Proses</span>
            <div class="text-2xl font-bold tracking-tight text-stone-900 mt-2">
                {{ $transaksiProses }} <span class="text-xs font-normal text-stone-500">order</span>
            </div>
            <p class="text-[11px] text-amber-700 font-medium mt-1">Dalam pencucian</p>
        </div>

        <div class="bg-white border border-stone-200 rounded-xl p-5 shadow-xs">
            <span class="text-xs font-medium text-stone-500">Transaksi Selesai</span>
            <div class="text-2xl font-bold tracking-tight text-stone-900 mt-2">
                {{ $transaksiSelesai }} <span class="text-xs font-normal text-stone-500">order</span>
            </div>
            <p class="text-[11px] text-emerald-700 font-medium mt-1">Siap diambil pelanggan</p>
        </div>

        <div class="bg-white border border-stone-200 rounded-xl p-5 shadow-xs">
            <span class="text-xs font-medium text-stone-500">Paket Layanan</span>
            <div class="text-2xl font-bold tracking-tight text-stone-900 mt-2">
                {{ $totalLayanan }} <span class="text-xs font-normal text-stone-500">paket</span>
            </div>
            <p class="text-[11px] text-stone-400 mt-1">Reguler, Ekspres, Superkilat</p>
        </div>
    </div>

    {{-- Dua Menu Navigasi Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Menu 1 --}}
        <a href="{{ route('layanan.index') }}" 
           class="group bg-white border border-stone-200 rounded-xl p-5 hover:border-stone-400 transition-colors shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase text-stone-400 group-hover:text-stone-900 transition-colors">Menu Navigasi 1</span>
                <span class="text-xs text-stone-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </div>
            <h2 class="text-base font-bold text-stone-900 mt-2">Data Layanan</h2>
            <p class="text-xs text-stone-500 mt-1">
                Kelola paket layanan cuci, tarif per kilogram, dan estimasi waktu selesai.
            </p>
        </a>

        {{-- Menu 2 --}}
        <a href="{{ route('transaksi.index') }}" 
           class="group bg-white border border-stone-200 rounded-xl p-5 hover:border-stone-400 transition-colors shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase text-stone-400 group-hover:text-stone-900 transition-colors">Menu Navigasi 2</span>
                <span class="text-xs text-stone-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </div>
            <h2 class="text-base font-bold text-stone-900 mt-2">Rekap Transaksi Laundry</h2>
            <p class="text-xs text-stone-500 mt-1">
                Pencatatan transaksi baru, update status pengerjaan, dan laporan keuangan.
            </p>
        </a>
    </div>

    {{-- Aktivitas Transaksi Terkini --}}
    <div class="bg-white border border-stone-200 rounded-xl overflow-hidden shadow-xs">
        <div class="px-5 py-3.5 border-b border-stone-200 flex items-center justify-between">
            <h2 class="text-xs font-bold uppercase tracking-wider text-stone-600">Aktivitas Transaksi Terbaru</h2>
            <a href="{{ route('transaksi.index') }}" class="text-xs text-stone-500 hover:text-stone-900 transition-colors">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-700">
                <thead class="bg-[#F6F5F2]/60 text-[11px] text-stone-500 font-semibold border-b border-stone-200">
                    <tr>
                        <th class="px-5 py-3">Pelanggan</th>
                        <th class="px-5 py-3">Jenis Layanan</th>
                        <th class="px-5 py-3">Berat</th>
                        <th class="px-5 py-3">Total Tarif</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($recentTransaksis as $trx)
                        <tr class="hover:bg-stone-50/70 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-stone-900">{{ $trx->nama_pelanggan }}</div>
                                <div class="text-[10px] text-stone-400 font-mono">{{ $trx->no_hp }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-stone-800">{{ $trx->layanan->nama_layanan ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-stone-800">
                                {{ number_format($trx->berat, 2) }} kg
                            </td>
                            <td class="px-5 py-3.5 font-bold text-stone-900">
                                Rp {{ number_format($trx->total_tarif, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if($trx->status === 'Proses')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Proses
                                    </span>
                                @elseif($trx->status === 'Selesai')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-stone-100 text-stone-700 border border-stone-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span>
                                        Masuk
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <form method="POST" action="{{ route('transaksi.update-status', $trx) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($trx->status === 'Masuk')
                                        <input type="hidden" name="status" value="Proses">
                                        <button type="submit" 
                                                class="px-2.5 py-1 text-xs font-medium bg-stone-100 hover:bg-stone-200 text-stone-800 border border-stone-200 rounded-md transition-colors cursor-pointer">
                                            Proses
                                        </button>
                                    @elseif($trx->status === 'Proses')
                                        <input type="hidden" name="status" value="Selesai">
                                        <button type="submit" 
                                                class="px-2.5 py-1 text-xs font-medium bg-emerald-900 hover:bg-emerald-950 text-white rounded-md transition-colors cursor-pointer">
                                            Tandai Selesai
                                        </button>
                                    @else
                                        <span class="text-[11px] text-stone-400 italic">Selesai</span>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-6 text-center text-stone-400">
                                Belum ada aktivitas transaksi terbaru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
