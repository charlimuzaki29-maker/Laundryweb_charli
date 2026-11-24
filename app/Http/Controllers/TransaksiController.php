<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransaksiRequest;
use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource (Rekap Transaksi & Laporan Keuangan).
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status');
        $layananFilter = $request->query('layanan_id');
        $search = $request->query('search');

        // Query transaksi dengan eager loading layanan untuk mencegah N+1
        $query = Transaksi::with('layanan')->latest();

        if (! empty($statusFilter) && in_array($statusFilter, ['Masuk', 'Proses', 'Selesai'], true)) {
            $query->where('status', $statusFilter);
        }

        if (! empty($layananFilter)) {
            $query->where('layanan_id', $layananFilter);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search): void {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $transaksis = $query->paginate(10)->withQueryString();

        // Laporan Keuangan & Rekap Keseluruhan
        $totalPendapatan = Transaksi::sum('total_tarif');
        $pendapatanSelesai = Transaksi::where('status', 'Selesai')->sum('total_tarif');
        $totalBerat = Transaksi::sum('berat');
        $totalTransaksi = Transaksi::count();
        $countMasuk = Transaksi::where('status', 'Masuk')->count();
        $countProses = Transaksi::where('status', 'Proses')->count();
        $countSelesai = Transaksi::where('status', 'Selesai')->count();

        // Rekap per jenis layanan
        $rekapLayanan = Layanan::withCount('transaksis')
            ->withSum('transaksis', 'total_tarif')
            ->withSum('transaksis', 'berat')
            ->get();

        $layanans = Layanan::orderBy('nama_layanan')->get();

        return view('transaksi.index', compact(
            'transaksis',
            'layanans',
            'statusFilter',
            'layananFilter',
            'search',
            'totalPendapatan',
            'pendapatanSelesai',
            'totalBerat',
            'totalTransaksi',
            'countMasuk',
            'countProses',
            'countSelesai',
            'rekapLayanan'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $layanans = Layanan::orderBy('nama_layanan')->get();

        return view('transaksi.create', compact('layanans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransaksiRequest $request): RedirectResponse
    {
        $layanan = Layanan::findOrFail($request->layanan_id);

        $berat = (float) $request->berat;
        // Hitung total tarif otomatis berdasarkan harga layanan per kg
        $totalTarif = round($berat * (float) $layanan->harga_per_kg);

        $transaksi = Transaksi::create([
            'layanan_id' => $layanan->id,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp' => $request->no_hp,
            'berat' => $berat,
            'total_tarif' => $totalTarif,
            'status' => $request->status ?? 'Masuk',
        ]);

        $formattedTarif = 'Rp '.number_format($totalTarif, 0, ',', '.');

        return redirect()->route('transaksi.index')
            ->with('success', "Transaksi untuk pelanggan '{$transaksi->nama_pelanggan}' berhasil dicatat! Total Tarif: {$formattedTarif}.");
    }

    /**
     * Update the status of the specified transaction.
     */
    public function updateStatus(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Masuk,Proses,Selesai'],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid.',
        ]);

        $statusLama = $transaksi->status;
        $statusBaru = $validated['status'];

        $transaksi->update(['status' => $statusBaru]);

        return back()->with('success', "Status pengerjaan cucian '{$transaksi->nama_pelanggan}' berhasil diubah dari '{$statusLama}' menjadi '{$statusBaru}'.");
    }

    /**
     * Remove the specified transaction.
     */
    public function destroy(Transaksi $transaksi): RedirectResponse
    {
        $namaPelanggan = $transaksi->nama_pelanggan;
        $transaksi->delete();

        return redirect()->route('transaksi.index')
            ->with('success', "Data transaksi pelanggan '{$namaPelanggan}' berhasil dihapus.");
    }
}
