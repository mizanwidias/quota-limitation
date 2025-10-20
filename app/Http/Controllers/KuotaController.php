<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('paket-kuota.index', [
            'title' => 'Kuota - Hyperlink',
        ]);
    }

    public function pilih($id)
    {
        // Data paket bisa kamu ambil dari array yang sama di index
        $pakets = [
            [
                'id' => 1,
                'nama' => 'Paket Silver',
                'deskripsi' => 'Internet cepat untuk kebutuhan harian.',
                'kuota' => '50 GB',
                'kecepatan' => '10 Mbps',
                'masa_aktif' => '30 Hari',
                'harga' => 100000,
                'warna' => 'primary',
                'ikon' => 'bi-wifi',
            ],
            [
                'id' => 2,
                'nama' => 'Paket Gold',
                'deskripsi' => 'Untuk streaming & gaming tanpa batas.',
                'kuota' => '100 GB',
                'kecepatan' => '20 Mbps',
                'masa_aktif' => '30 Hari',
                'harga' => 180000,
                'warna' => 'success',
                'ikon' => 'bi-lightning-fill',
            ],
            [
                'id' => 3,
                'nama' => 'Paket Platinum',
                'deskripsi' => 'Performa maksimal untuk semua kebutuhan.',
                'kuota' => 'Unlimited',
                'kecepatan' => '50 Mbps',
                'masa_aktif' => '30 Hari',
                'harga' => 250000,
                'warna' => 'warning',
                'ikon' => 'bi-star-fill',
            ],
        ];

        // Cari paket berdasarkan ID
        $paket = collect($pakets)->firstWhere('id', (int)$id);

        if (!$paket) {
            abort(404, 'Paket tidak ditemukan.');
        }

        return view('paket-kuota.pilih', [
            'title' => 'Pilih Paket - ' . $paket['nama'],
            'paket' => $paket,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
