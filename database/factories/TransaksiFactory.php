<?php

namespace Database\Factories;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaksi>
 */
class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $layanan = Layanan::inRandomOrder()->first() ?? Layanan::factory()->create();
        $berat = fake()->randomFloat(2, 1, 15);
        $totalTarif = round($berat * $layanan->harga_per_kg);

        return [
            'layanan_id' => $layanan->id,
            'nama_pelanggan' => fake()->name(),
            'no_hp' => fake()->numerify('08##########'),
            'berat' => $berat,
            'total_tarif' => $totalTarif,
            'status' => fake()->randomElement(['Masuk', 'Proses', 'Selesai']),
        ];
    }
}
