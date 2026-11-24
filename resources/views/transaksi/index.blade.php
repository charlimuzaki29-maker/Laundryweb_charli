@extends('layouts.app')

@section('title', 'Rekap Transaksi Laundry')

@section('content')
<div class="space-y-6">
    {{-- Header Konten & Tombol Aksi --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-stone-900">Rekap Transaksi Laundry</h1>
            <p class="text-xs text-stone-500 mt-0.5">Ringkasan keuangan dan pencatatan transaksi pelanggan</p>
        </div>

        <div>
            <a href="{{ route('transaksi.create') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-900 hover:bg-emerald-950 text-white text-xs font-medium rounded-lg transition-colors cursor-pointer">
                <span>+ Catat Transaksi</span>
            </a>
        </div>
    </div>

    {{-- Bar Metrik Horizontal: 3 Metrik Ringkas Dipisah Garis Vertikal Tipis --}}
    <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-stone-200 bg-white border border-stone-200 rounded-xl shadow-xs">
        {{-- 1. Total Pendapatan --}}
        <div class="p-5">
            <span class="text-xs font-medium text-stone-500">Total Pendapatan</span>
            <div class="text-2xl font-bold tracking-tight text-stone-900 mt-1">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-stone-400 mt-1">{{ $totalTransaksi }} transaksi tercatat</p>
        </div>

        {{-- 2. Transaksi Proses (dengan titik kuning) --}}
        <div class="p-5">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                <span class="text-xs font-medium text-stone-500">Transaksi Proses</span>
            </div>
            <div class="text-2xl font-bold tracking-tight text-stone-900 mt-1">
                {{ $countProses }} <span class="text-xs font-normal text-stone-500">cucian</span>
            </div>
            <p class="text-[11px] text-amber-700 font-medium mt-1">Sedang dalam pengerjaan</p>
        </div>

        {{-- 3. Transaksi Selesai (dengan titik hijau) --}}
        <div class="p-5">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                <span class="text-xs font-medium text-stone-500">Transaksi Selesai</span>
            </div>
            <div class="text-2xl font-bold tracking-tight text-stone-900 mt-1">
                {{ $countSelesai }} <span class="text-xs font-normal text-stone-500">cucian</span>
            </div>
            <p class="text-[11px] text-emerald-700 font-medium mt-1">
                Rp {{ number_format($pendapatanSelesai, 0, ',', '.') }} telah rampung
            </p>
        </div>
    </div>

    {{-- Filter & Pencarian Minimalis --}}
    <div class="bg-white border border-stone-200 rounded-xl p-3.5">
        <form method="GET" action="{{ route('transaksi.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-2.5 items-center">
            <div>
                <select name="status" class="w-full px-2.5 py-1.5 rounded-lg border border-stone-200 text-xs text-stone-800 bg-white focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900">
                    <option value="">Semua Status</option>
                    <option value="Masuk" {{ $statusFilter === 'Masuk' ? 'selected' : '' }}>Masuk</option>
                    <option value="Proses" {{ $statusFilter === 'Proses' ? 'selected' : '' }}>Proses</option>
                    <option value="Selesai" {{ $statusFilter === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div>
                <select name="layanan_id" class="w-full px-2.5 py-1.5 rounded-lg border border-stone-200 text-xs text-stone-800 bg-white focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900">
                    <option value="">Semua Layanan</option>
                    @foreach($layanans as $lay)
                        <option value="{{ $lay->id }}" {{ $layananFilter == $lay->id ? 'selected' : '' }}>
                            {{ $lay->nama_layanan }} (Rp {{ number_format($lay->harga_per_kg, 0, ',', '.') }}/kg)
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari pelanggan / no HP..." 
                       class="w-full px-2.5 py-1.5 rounded-lg border border-stone-200 text-xs text-stone-800 placeholder:text-stone-400 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900">
            </div>

            <div class="flex items-center gap-1.5">
                <button type="submit" 
                        class="w-full py-1.5 px-3 bg-stone-900 hover:bg-stone-800 text-white text-xs font-medium rounded-lg transition-colors cursor-pointer text-center">
                    Filter
                </button>
                @if($statusFilter || $layananFilter || $search)
                    <a href="{{ route('transaksi.index') }}" 
                       class="py-1.5 px-3 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-medium rounded-lg transition-colors text-center whitespace-nowrap">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Kontainer Tabel Rekap Transaksi --}}
    <div class="bg-white border border-stone-200 rounded-xl overflow-hidden shadow-xs">
        {{-- Header Kontainer --}}
        <div class="px-5 py-3.5 border-b border-stone-200 flex items-center justify-between">
            <h2 class="text-xs font-bold uppercase tracking-wider text-stone-600">Daftar Transaksi</h2>
            <span class="text-xs text-stone-400 font-mono">{{ $transaksis->total() }} total</span>
        </div>

        {{-- 1. Tampilan Desktop (Tabel bersih tanpa garis vertikal, divide-y divide-stone-200) --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-700">
                <thead class="bg-[#F6F5F2]/60 text-[11px] text-stone-500 font-semibold border-b border-stone-200">
                    <tr>
                        <th class="px-5 py-3">Pelanggan</th>
                        <th class="px-5 py-3">No HP</th>
                        <th class="px-5 py-3">Jenis Layanan</th>
                        <th class="px-5 py-3">Berat</th>
                        <th class="px-5 py-3">Total Tarif</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($transaksis as $trx)
                        <tr class="hover:bg-stone-50/70 transition-colors">
                            <td class="px-5 py-3.5 font-medium text-stone-900">
                                {{ $trx->nama_pelanggan }}
                                <div class="text-[10px] text-stone-400">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-stone-500 font-mono">
                                {{ $trx->no_hp }}
                            </td>
                            <td class="px-5 py-3.5 text-stone-800">
                                {{ $trx->layanan->nama_layanan ?? '-' }}
                                <span class="text-[10px] text-stone-400 block">@ Rp {{ number_format($trx->layanan->harga_per_kg ?? 0, 0, ',', '.') }}/kg</span>
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
                                <div class="inline-flex items-center gap-2">
                                    {{-- Aksi update status --}}
                                    @if($trx->status === 'Masuk')
                                        <form method="POST" action="{{ route('transaksi.update-status', $trx) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Proses">
                                            <button type="submit" 
                                                    class="px-2.5 py-1 text-xs font-medium bg-stone-100 hover:bg-stone-200 text-stone-800 border border-stone-200 rounded-md transition-colors cursor-pointer">
                                                Proses
                                            </button>
                                        </form>
                                    @elseif($trx->status === 'Proses')
                                        <form method="POST" action="{{ route('transaksi.update-status', $trx) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Selesai">
                                            <button type="submit" 
                                                    class="px-2.5 py-1 text-xs font-medium bg-emerald-900 hover:bg-emerald-950 text-white rounded-md transition-colors cursor-pointer">
                                                Tandai Selesai
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Hapus --}}
                                    <form method="POST" action="{{ route('transaksi.destroy', $trx) }}" 
                                          onsubmit="return confirm('Hapus transaksi \'{{ $trx->nama_pelanggan }}\'?')" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-rose-700 hover:bg-rose-50 px-2 py-1 rounded-md text-xs font-medium transition-colors cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-stone-400">
                                Tidak ada data transaksi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 2. Tampilan Mobile (Tumpukan kartu vertikal rapi, divide-y divide-stone-200) --}}
        <div class="block md:hidden divide-y divide-stone-200">
            @forelse($transaksis as $trx)
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="font-bold text-sm text-stone-900">{{ $trx->nama_pelanggan }}</div>
                            <div class="text-[11px] text-stone-400 font-mono">{{ $trx->no_hp }} &bull; {{ $trx->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div>
                            @if($trx->status === 'Proses')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Proses
                                </span>
                            @elseif($trx->status === 'Selesai')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-stone-100 text-stone-700 border border-stone-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span>
                                    Masuk
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs bg-[#F6F5F2]/50 p-2.5 rounded-lg border border-stone-200">
                        <div>
                            <span class="text-stone-400 text-[10px] block">Layanan:</span>
                            <span class="font-medium text-stone-800">{{ $trx->layanan->nama_layanan ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-stone-400 text-[10px] block">Berat:</span>
                            <span class="font-medium text-stone-800">{{ number_format($trx->berat, 2) }} kg</span>
                        </div>
                        <div class="col-span-2 pt-1 border-t border-stone-200 flex items-center justify-between">
                            <span class="text-stone-500 font-medium">Total Tarif:</span>
                            <span class="font-bold text-stone-900 text-sm">Rp {{ number_format($trx->total_tarif, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Action Row Mobile --}}
                    <div class="flex items-center justify-between pt-1">
                        <div>
                            @if($trx->status === 'Masuk')
                                <form method="POST" action="{{ route('transaksi.update-status', $trx) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Proses">
                                    <button type="submit" 
                                            class="px-3 py-1.5 text-xs font-medium bg-stone-100 hover:bg-stone-200 text-stone-800 border border-stone-200 rounded-md transition-colors cursor-pointer">
                                        Mulai Proses
                                    </button>
                                </form>
                            @elseif($trx->status === 'Proses')
                                <form method="POST" action="{{ route('transaksi.update-status', $trx) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Selesai">
                                    <button type="submit" 
                                            class="px-3 py-1.5 text-xs font-medium bg-emerald-900 hover:bg-emerald-950 text-white rounded-md transition-colors cursor-pointer">
                                        Tandai Selesai
                                    </button>
                                </form>
                            @else
                                <span class="text-[11px] text-stone-400 italic">Selesai dikerjakan</span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('transaksi.destroy', $trx) }}" 
                              onsubmit="return confirm('Hapus transaksi \'{{ $trx->nama_pelanggan }}\'?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-rose-700 hover:bg-rose-50 px-2 py-1 rounded-md text-xs font-medium transition-colors cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-xs text-stone-400">
                    Tidak ada data transaksi.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($transaksis->hasPages())
            <div class="px-5 py-3 border-t border-stone-200 bg-[#F6F5F2]/40">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
