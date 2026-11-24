<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_layanan_list(): void
    {
        $user = User::factory()->admin()->create();

        Layanan::factory()->create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'estimasi_waktu' => '3-4 hari',
        ]);

        $response = $this->actingAs($user)->get(route('layanan.index'));

        $response->assertStatus(200);
        $response->assertSee('Reguler');
        $response->assertSee('7.000');
        $response->assertSee('3-4 hari');
    }

    public function test_admin_can_create_new_layanan(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('layanan.store'), [
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'estimasi_waktu' => '5 jam selesai',
        ]);

        $response->assertRedirect(route('layanan.index'));
        $this->assertDatabaseHas('layanans', [
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'estimasi_waktu' => '5 jam selesai',
        ]);
    }

    public function test_create_layanan_validation_requires_all_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('layanan.store'), [
            'nama_layanan' => '',
            'harga_per_kg' => '',
            'estimasi_waktu' => '',
        ]);

        $response->assertSessionHasErrors(['nama_layanan', 'harga_per_kg', 'estimasi_waktu']);
    }

    public function test_admin_can_update_layanan(): void
    {
        $admin = User::factory()->admin()->create();
        $layanan = Layanan::factory()->create([
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 10000,
            'estimasi_waktu' => '2 hari',
        ]);

        $response = $this->actingAs($admin)->put(route('layanan.update', $layanan), [
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 12000,
            'estimasi_waktu' => '1-2 hari',
        ]);

        $response->assertRedirect(route('layanan.index'));
        $this->assertDatabaseHas('layanans', [
            'id' => $layanan->id,
            'harga_per_kg' => 12000,
            'estimasi_waktu' => '1-2 hari',
        ]);
    }

    public function test_admin_can_delete_layanan(): void
    {
        $admin = User::factory()->admin()->create();
        $layanan = Layanan::factory()->create([
            'nama_layanan' => 'Paket Tambahan',
            'harga_per_kg' => 5000,
            'estimasi_waktu' => '5 hari',
        ]);

        $response = $this->actingAs($admin)->delete(route('layanan.destroy', $layanan));

        $response->assertRedirect(route('layanan.index'));
        $this->assertDatabaseMissing('layanans', [
            'id' => $layanan->id,
        ]);
    }

    public function test_kasir_cannot_store_update_or_delete_layanan(): void
    {
        $kasir = User::factory()->kasir()->create();
        $layanan = Layanan::factory()->create();

        // Kasir cannot store
        $storeResponse = $this->actingAs($kasir)->post(route('layanan.store'), [
            'nama_layanan' => 'Illegal Service',
            'harga_per_kg' => 10000,
            'estimasi_waktu' => '1 hari',
        ]);
        $storeResponse->assertStatus(403);

        // Kasir cannot update
        $updateResponse = $this->actingAs($kasir)->put(route('layanan.update', $layanan), [
            'nama_layanan' => 'Updated Service',
            'harga_per_kg' => 15000,
            'estimasi_waktu' => '1 hari',
        ]);
        $updateResponse->assertStatus(403);

        // Kasir cannot delete
        $deleteResponse = $this->actingAs($kasir)->delete(route('layanan.destroy', $layanan));
        $deleteResponse->assertStatus(403);
    }
}
