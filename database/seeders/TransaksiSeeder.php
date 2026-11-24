<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reguler = Layanan::where('nama_layanan', 'Reguler')->first();
        $ekspres = Layanan::where('nama_layanan', 'Ekspres')->first();
        $superkilat = Layanan::where('nama_layanan', 'Superkilat')->first();

        $transaksis = [
            [
                'layanan_id' => $reguler ? $reguler->id : 1,
                'nama_pelanggan' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'berat' => 3.00,
                'total_tarif' => 21000,
                'status' => 'Selesai',
                'created_at' => now()->subDays(2),
            ],
            [
                'layanan_id' => $ekspres ? $ekspres->id : 2,
                'nama_pelanggan' => 'Siti Nurhaliza',
                'no_hp' => '082198765432',
                'berat' => 4.50,
                'total_tarif' => 54000,
                'status' => 'Proses',
                'created_at' => now()->subDay(),
            ],
            [
                'layanan_id' => $superkilat ? $superkilat->id : 3,
                'nama_pelanggan' => 'Ahmad Fauzi',
                'no_hp' => '085712344321',
                'berat' => 2.00,
                'total_tarif' => 50000,
                'status' => 'Masuk',
                'created_at' => now(),
            ],
        ];

        foreach ($transaksis as $trx) {
            Transaksi::create($trx);
        }
    }
}
