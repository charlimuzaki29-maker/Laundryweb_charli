@extends('layouts.app')

@section('title', 'Tambah Layanan')

@section('content')
<div class="max-w-md mx-auto space-y-5">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-stone-900">Tambah Layanan</h1>
            <p class="text-xs text-stone-500 mt-0.5">Buat paket jenis layanan cuci baru</p>
        </div>
        <a href="{{ route('layanan.index') }}" 
           class="text-xs font-medium text-stone-500 hover:text-stone-900 transition-colors">
            &larr; Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white border border-stone-200 rounded-xl p-6">
        <form method="POST" action="{{ route('layanan.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="nama_layanan" class="block text-xs font-medium text-stone-700 mb-1.5">
                    Nama / Jenis Layanan <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="nama_layanan" 
                       id="nama_layanan" 
                       value="{{ old('nama_layanan') }}" 
                       required
                       placeholder="Contoh: Reguler, Ekspres, Superkilat"
                       class="w-full px-3 py-2 text-xs rounded-lg border border-stone-300 text-stone-900 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900 transition-colors">
                @error('nama_layanan')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="harga_per_kg" class="block text-xs font-medium text-stone-700 mb-1.5">
                    Tarif per Kilogram (Rp) <span class="text-rose-500">*</span>
                </label>
                <input type="number" 
                       step="any"
                       min="0"
                       name="harga_per_kg" 
                       id="harga_per_kg" 
                       value="{{ old('harga_per_kg') }}" 
                       required
                       placeholder="7000"
                       class="w-full px-3 py-2 text-xs rounded-lg border border-stone-300 text-stone-900 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900 transition-colors">
                @error('harga_per_kg')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="estimasi_waktu" class="block text-xs font-medium text-stone-700 mb-1.5">
                    Estimasi Waktu Selesai <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="estimasi_waktu" 
                       id="estimasi_waktu" 
                       value="{{ old('estimasi_waktu') }}" 
                       required
                       placeholder="Contoh: 3-4 hari, 1-2 hari, 5 jam selesai"
                       class="w-full px-3 py-2 text-xs rounded-lg border border-stone-300 text-stone-900 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900 transition-colors">
                @error('estimasi_waktu')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-3 border-t border-stone-100 flex items-center justify-end gap-2.5">
                <a href="{{ route('layanan.index') }}" 
                   class="px-3.5 py-2 text-xs font-medium text-stone-600 hover:text-stone-900 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-emerald-900 hover:bg-emerald-950 text-white text-xs font-medium rounded-lg transition-colors cursor-pointer">
                    Simpan Layanan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
