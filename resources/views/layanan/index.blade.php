@extends('layouts.app')

@section('title', 'Data Layanan')

@section('content')
<div class="space-y-6">
    {{-- Header Halaman --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-stone-900">Data Layanan</h1>
            <p class="text-xs text-stone-500 mt-0.5">Daftar paket cuci, tarif per kilogram, dan estimasi waktu selesai</p>
        </div>

        <div>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('layanan.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-900 hover:bg-emerald-950 text-white text-xs font-medium rounded-lg transition-colors cursor-pointer">
                    <span>+ Tambah Layanan</span>
                </a>
            @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-medium bg-stone-100 text-stone-600 border border-stone-200">
                    Mode Baca (Kasir)
                </span>
            @endif
        </div>
    </div>

    {{-- Card Grid 3 Kolom Responsif --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($layanans as $layanan)
            <div class="bg-white border border-stone-200 rounded-xl p-5 flex flex-col justify-between hover:border-stone-300 transition-colors">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-stone-400">Paket Layanan</span>
                        <span class="text-[11px] text-stone-400 font-mono">{{ $layanan->transaksis_count ?? 0 }} transaksi</span>
                    </div>

                    <h2 class="text-base font-bold text-stone-900 mt-2">{{ $layanan->nama_layanan }}</h2>

                    <div class="mt-3">
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-bold tracking-tight text-stone-900">
                                Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-stone-500 font-normal">/ kg</span>
                        </div>
                        <p class="text-xs text-stone-500 mt-1 flex items-center gap-1">
                            <span>Estimasi pengerjaan:</span>
                            <span class="text-stone-800 font-medium">{{ $layanan->estimasi_waktu }}</span>
                        </p>
                    </div>
                </div>

                {{-- Garis Pemisah & Tombol Aksi --}}
                <div class="mt-5 pt-3.5 border-t border-stone-100 flex items-center justify-between text-xs">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('layanan.edit', $layanan) }}" 
                           class="text-stone-600 hover:text-stone-900 font-medium transition-colors">
                            Ubah
                        </a>

                        <form method="POST" action="{{ route('layanan.destroy', $layanan) }}" 
                              onsubmit="return confirm('Hapus jenis layanan \'{{ $layanan->nama_layanan }}\'?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-rose-700 hover:bg-rose-50 px-2 py-1 rounded-md transition-colors cursor-pointer font-medium">
                                Hapus
                            </button>
                        </form>
                    @else
                        <span class="text-[11px] text-stone-400">Hanya Admin yang dapat mengubah</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-stone-200 rounded-xl p-8 text-center text-xs text-stone-400">
                Belum ada data paket layanan yang tersedia.
            </div>
        @endforelse
    </div>
</div>
@endsection
