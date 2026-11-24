<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLayananRequest;
use App\Http\Requests\UpdateLayananRequest;
use App\Models\Layanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $layanans = Layanan::withCount('transaksis')->orderBy('harga_per_kg', 'asc')->get();

        return view('layanan.index', compact('layanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('layanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLayananRequest $request): RedirectResponse
    {
        Layanan::create($request->validated());

        return redirect()->route('layanan.index')
            ->with('success', 'Jenis layanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layanan $layanan): View
    {
        return view('layanan.edit', compact('layanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLayananRequest $request, Layanan $layanan): RedirectResponse
    {
        $layanan->update($request->validated());

        return redirect()->route('layanan.index')
            ->with('success', 'Jenis layanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layanan $layanan): RedirectResponse
    {
        $namaLayanan = $layanan->nama_layanan;
        $layanan->delete();

        return redirect()->route('layanan.index')
            ->with('success', "Jenis layanan '{$namaLayanan}' berhasil dihapus.");
    }
}
