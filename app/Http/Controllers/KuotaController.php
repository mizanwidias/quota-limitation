<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function getPakets()
    {
        return [
            [
                'id' => 1,
                'nama' => 'Paket Silver',
                'deskripsi' => 'Internet cepat untuk kebutuhan harian.',
                'kuota' => '20 GB',
                'kecepatan' => '15 Mbps',
                'masa_aktif' => '30 Hari',
                'harga' => 50000,
                'warna' => 'primary',
                'ikon' => 'bi-wifi',
                'fitur' => [
                    'Browsing Lancar',
                    'Streaming SD',
                    'Social Media'
                ],
                'badge' => null
            ],
            [
                'id' => 2,
                'nama' => 'Paket Gold',
                'deskripsi' => 'Untuk streaming & gaming tanpa batas.',
                'kuota' => '50 GB',
                'kecepatan' => '25 Mbps',
                'masa_aktif' => '30 Hari',
                'harga' => 100000,
                'warna' => 'success',
                'ikon' => 'bi-lightning-fill',
                'fitur' => [
                    'Streaming HD',
                    'Gaming Online',
                    'Video Call HD'
                ],
                'badge' => 'Popular'
            ],
            [
                'id' => 3,
                'nama' => 'Paket Platinum',
                'deskripsi' => 'Performa maksimal untuk semua kebutuhan.',
                'kuota' => 'Unlimited',
                'kecepatan' => '50 Mbps',
                'masa_aktif' => '30 Hari',
                'harga' => 180000,
                'warna' => 'warning',
                'ikon' => 'bi-star-fill',
                'fitur' => [
                    'Streaming 4K',
                    'Download Unlimited',
                    'Priority Support'
                ],
                'badge' => 'Best Value'
            ],
            [
                'id' => 4,
                'nama' => 'Paket Diamond',
                'deskripsi' => 'Kecepatan super untuk professional.',
                'kuota' => 'Unlimited',
                'kecepatan' => '100 Mbps',
                'masa_aktif' => '30 Hari',
                'harga' => 250000,
                'warna' => 'danger',
                'ikon' => 'bi-gem',
                'fitur' => [
                    'Ultra Fast Speed',
                    'Premium Support 24/7',
                    'Free Router'
                ],
                'badge' => 'Premium'
            ],
        ];
    }

    public function index()
    {
        $pakets = $this->getPakets();

        return view('paket-kuota.index', [
            'title' => 'Kuota - Hyperlink',
            'pakets' => $pakets,
        ]);
    }

    public function pilih($id)
    {
        $pakets = $this->getPakets();
        
        // Cari paket berdasarkan ID
        $paket = collect($pakets)->firstWhere('id', (int)$id);

        if (!$paket) {
            abort(404, 'Paket tidak ditemukan.');
        }

        return view('paket-kuota.pilih', [
            'title' => 'Pilih Paket - ' . $paket['nama'],
            'paket' => $paket,
            'pakets' => $pakets, // kirim semua paket untuk comparison
        ]);
    }

    public function proses(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        $pakets = $this->getPakets();
        $paket = collect($pakets)->firstWhere('id', (int)$id);

        if (!$paket) {
            return redirect()->route('kuota')
                ->with('error', 'Paket tidak ditemukan.');
        }

        // Di sini bisa simpan ke database
        // Untuk sekarang redirect dengan success message
        return redirect()->route('kuota')
            ->with('success', 'Pemesanan paket ' . $paket['nama'] . ' berhasil! Kami akan menghubungi Anda segera.');
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
