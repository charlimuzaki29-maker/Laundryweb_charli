<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(): View
    {
        $totalPendapatan = Transaksi::sum('total_tarif');
        $totalTransaksi = Transaksi::count();
        $transaksiMasuk = Transaksi::where('status', 'Masuk')->count();
        $transaksiProses = Transaksi::where('status', 'Proses')->count();
        $transaksiSelesai = Transaksi::where('status', 'Selesai')->count();
        $totalLayanan = Layanan::count();

        // 5 Transaksi terbaru dengan eager loading relasi layanan (prevent N+1)
        $recentTransaksis = Transaksi::with('layanan')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalPendapatan',
            'totalTransaksi',
            'transaksiMasuk',
            'transaksiProses',
            'transaksiSelesai',
            'totalLayanan',
            'recentTransaksis'
        ));
    }
}
