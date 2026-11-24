<?php

namespace Database\Factories;

use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Layanan>
 */
class LayananFactory extends Factory
{
    protected $model = Layanan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_layanan' => fake()->randomElement(['Reguler', 'Ekspres', 'Superkilat']),
            'harga_per_kg' => fake()->randomElement([7000, 12000, 25000]),
            'estimasi_waktu' => fake()->randomElement(['3-4 hari', '1-2 hari', '5 jam selesai']),
        ];
    }
}
