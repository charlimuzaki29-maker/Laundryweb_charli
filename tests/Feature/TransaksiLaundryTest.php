<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiLaundryTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaksi_index_displays_rekap_and_financial_report(): void
    {
        $user = User::factory()->kasir()->create();

        $layanan = Layanan::factory()->create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'estimasi_waktu' => '3-4 hari',
        ]);

        Transaksi::factory()->create([
            'layanan_id' => $layanan->id,
            'nama_pelanggan' => 'Siti Aminah',
            'no_hp' => '08123456789',
            'berat' => 4.00,
            'total_tarif' => 28000,
            'status' => 'Selesai',
        ]);

        $response = $this->actingAs($user)->get(route('transaksi.index'));

        $response->assertStatus(200);
        $response->assertSee('Rekap Transaksi Laundry');
        $response->assertSee('Siti Aminah');
        $response->assertSee('Reguler');
        $response->assertSee('28.000');
        $response->assertSee('Selesai');
    }

    public function test_can_create_new_transaction_with_automatic_tariff_calculation(): void
    {
        $kasir = User::factory()->kasir()->create();

        $layanan = Layanan::factory()->create([
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 12000,
            'estimasi_waktu' => '1-2 hari',
        ]);

        // Input 3.5 kg on Ekspres (12.000 / kg) -> 3.5 * 12.000 = 42.000
        $response = $this->actingAs($kasir)->post(route('transaksi.store'), [
            'nama_pelanggan' => 'Budi Pratama',
            'no_hp' => '087811223344',
            'layanan_id' => $layanan->id,
            'berat' => 3.5,
            'status' => 'Masuk',
        ]);

        $response->assertRedirect(route('transaksi.index'));

        $this->assertDatabaseHas('transaksis', [
            'nama_pelanggan' => 'Budi Pratama',
            'no_hp' => '087811223344',
            'layanan_id' => $layanan->id,
            'berat' => 3.50,
            'total_tarif' => 42000,
            'status' => 'Masuk',
        ]);
    }

    public function test_transaction_creation_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('transaksi.store'), [
            'nama_pelanggan' => '',
            'no_hp' => '',
            'layanan_id' => '',
            'berat' => '',
        ]);

        $response->assertSessionHasErrors(['nama_pelanggan', 'no_hp', 'layanan_id', 'berat']);
    }

    public function test_transaction_status_update_from_masuk_to_proses(): void
    {
        $user = User::factory()->kasir()->create();
        $transaksi = Transaksi::factory()->create(['status' => 'Masuk']);

        $response = $this->actingAs($user)->patch(route('transaksi.update-status', $transaksi), [
            'status' => 'Proses',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'status' => 'Proses',
        ]);
    }

    public function test_transaction_status_update_from_proses_to_selesai(): void
    {
        $user = User::factory()->kasir()->create();
        $transaksi = Transaksi::factory()->create(['status' => 'Proses']);

        $response = $this->actingAs($user)->patch(route('transaksi.update-status', $transaksi), [
            'status' => 'Selesai',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_can_delete_transaction(): void
    {
        $user = User::factory()->admin()->create();
        $transaksi = Transaksi::factory()->create();

        $response = $this->actingAs($user)->delete(route('transaksi.destroy', $transaksi));

        $response->assertRedirect(route('transaksi.index'));
        $this->assertDatabaseMissing('transaksis', [
            'id' => $transaksi->id,
        ]);
    }
}
