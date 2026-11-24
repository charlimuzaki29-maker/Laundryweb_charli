<?php

namespace App\Models;

use Database\Factories\LayananFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_layanan', 'harga_per_kg', 'estimasi_waktu'])]
class Layanan extends Model
{
    /** @use HasFactory<LayananFactory> */
    use HasFactory;

    /**
     * Get the transaksis for the layanan.
     */
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'layanan_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga_per_kg' => 'decimal:2',
        ];
    }
}
