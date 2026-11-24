@extends('layouts.app')

@section('title', 'Catat Transaksi')

@section('content')
<div class="max-w-xl mx-auto space-y-5">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-stone-900">Catat Transaksi Baru</h1>
            <p class="text-xs text-stone-500 mt-0.5">Input cucian pelanggan dengan kalkulasi tarif otomatis</p>
        </div>
        <a href="{{ route('transaksi.index') }}" 
           class="text-xs font-medium text-stone-500 hover:text-stone-900 transition-colors">
            &larr; Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white border border-stone-200 rounded-xl p-6 sm:p-7 shadow-xs">
        <form method="POST" action="{{ route('transaksi.store') }}" id="transaksiForm" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Nama Pelanggan --}}
                <div>
                    <label for="nama_pelanggan" class="block text-xs font-medium text-stone-700 mb-1.5">
                        Nama Pelanggan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_pelanggan" 
                           id="nama_pelanggan" 
                           value="{{ old('nama_pelanggan') }}" 
                           required
                           placeholder="Contoh: Budi Santoso"
                           class="w-full px-3 py-2 text-xs rounded-lg border border-stone-300 text-stone-900 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900 transition-colors">
                    @error('nama_pelanggan')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No HP Pelanggan --}}
                <div>
                    <label for="no_hp" class="block text-xs font-medium text-stone-700 mb-1.5">
                        Nomor Handphone (WhatsApp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="no_hp" 
                           id="no_hp" 
                           value="{{ old('no_hp') }}" 
                           required
                           placeholder="Contoh: 081234567890"
                           class="w-full px-3 py-2 text-xs rounded-lg border border-stone-300 text-stone-900 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900 transition-colors">
                    @error('no_hp')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Jenis Layanan --}}
                <div>
                    <label for="layanan_id" class="block text-xs font-medium text-stone-700 mb-1.5">
                        Jenis Layanan <span class="text-rose-500">*</span>
                    </label>
                    <select name="layanan_id" 
                            id="layanan_id" 
                            required 
                            class="w-full px-3 py-2 text-xs rounded-lg border border-stone-300 text-stone-900 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900 transition-colors bg-white">
                        <option value="" disabled {{ old('layanan_id') ? '' : 'selected' }}>-- Pilih Layanan --</option>
                        @foreach($layanans as $lay)
                            <option value="{{ $lay->id }}" 
                                    data-harga="{{ (float)$lay->harga_per_kg }}"
                                    data-nama="{{ $lay->nama_layanan }}"
                                    data-estimasi="{{ $lay->estimasi_waktu }}"
                                    {{ old('layanan_id') == $lay->id ? 'selected' : '' }}>
                                {{ $lay->nama_layanan }} (Rp {{ number_format($lay->harga_per_kg, 0, ',', '.') }}/kg)
                            </option>
                        @endforeach
                    </select>
                    @error('layanan_id')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Berat Cucian --}}
                <div>
                    <label for="berat" class="block text-xs font-medium text-stone-700 mb-1.5">
                        Berat (Kg) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           min="0.01"
                           name="berat" 
                           id="berat" 
                           value="{{ old('berat') }}" 
                           required
                           placeholder="Contoh: 3.5"
                           class="w-full px-3 py-2 text-xs rounded-lg border border-stone-300 text-stone-900 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900 transition-colors">
                    @error('berat')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Status Awal --}}
            <div>
                <label class="block text-xs font-medium text-stone-700 mb-1.5">Status Awal</label>
                <div class="flex items-center gap-4 text-xs">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="Masuk" checked class="w-3.5 h-3.5 text-emerald-900 focus:ring-emerald-900">
                        <span class="text-stone-800">Masuk (Diterima)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="Proses" class="w-3.5 h-3.5 text-emerald-900 focus:ring-emerald-900">
                        <span class="text-stone-800">Langsung Proses</span>
                    </label>
                </div>
            </div>

            {{-- Kalkulasi Otomatis (Box Minimalist Warm Stone) --}}
            <div class="p-4 rounded-xl bg-[#F6F5F2]/70 border border-stone-200 space-y-3">
                <div class="flex items-center justify-between text-xs text-stone-500">
                    <span>Estimasi Pengerjaan:</span>
                    <strong id="displayEstimasi" class="text-stone-800 font-medium">-</strong>
                </div>
                <div class="flex items-center justify-between text-xs text-stone-500">
                    <span>Tarif per Kg:</span>
                    <strong id="displayHargaPerKg" class="text-stone-800 font-medium">-</strong>
                </div>
                <div class="pt-2 border-t border-stone-200 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-stone-900">Total Tarif Otomatis</div>
                        <div class="text-[10px] text-stone-400">Berat &times; Tarif Layanan</div>
                    </div>
                    <div id="displayTotalTarif" class="text-xl font-bold tracking-tight text-emerald-950">
                        Rp 0
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="pt-3 border-t border-stone-100 flex items-center justify-end gap-2.5">
                <a href="{{ route('transaksi.index') }}" 
                   class="px-3.5 py-2 text-xs font-medium text-stone-600 hover:text-stone-900 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-emerald-900 hover:bg-emerald-950 text-white text-xs font-medium rounded-lg transition-colors cursor-pointer">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const layananSelect = document.getElementById('layanan_id');
        const beratInput = document.getElementById('berat');
        const displayHarga = document.getElementById('displayHargaPerKg');
        const displayEstimasi = document.getElementById('displayEstimasi');
        const displayTotal = document.getElementById('displayTotalTarif');

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(number);
        }

        function calculateTarif() {
            const selectedOption = layananSelect.options[layananSelect.selectedIndex];
            const harga = selectedOption ? parseFloat(selectedOption.getAttribute('data-harga')) || 0 : 0;
            const estimasi = selectedOption ? (selectedOption.getAttribute('data-estimasi') || '-') : '-';
            const berat = parseFloat(beratInput.value) || 0;

            if (harga > 0) {
                displayHarga.textContent = formatRupiah(harga) + ' / kg';
                displayEstimasi.textContent = estimasi;
            } else {
                displayHarga.textContent = '-';
                displayEstimasi.textContent = '-';
            }

            if (berat > 0) {
                displayBerat.textContent = berat.toFixed(2) + ' kg';
            }

            const total = Math.round(harga * berat);
            displayTotal.textContent = formatRupiah(total);
        }

        layananSelect.addEventListener('change', calculateTarif);
        beratInput.addEventListener('input', calculateTarif);

        calculateTarif();
    });
</script>
@endsection
