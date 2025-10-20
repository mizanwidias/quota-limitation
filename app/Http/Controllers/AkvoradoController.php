<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AkvoradoController extends Controller
{
    public function topServices()
    {
        try {
            $response = Http::get('http://69.69.69.2:8080/api/v0/flows', [
                'limit' => 1000,
                'sort' => '-bytes',
            ]);

            if ($response->failed()) {
                return back()->with('error', 'Gagal mengambil data dari Akvorado.');
            }

            $flows = $response->json();

            // Pastikan data valid
            if (!is_array($flows)) {
                return back()->with('error', 'Format data Akvorado tidak sesuai.');
            }

            // Group berdasarkan domain / service
            $services = collect($flows)
                ->groupBy('dst_name')
                ->map(function ($group) {
                    return $group->sum('bytes');
                })
                ->sortDesc()
                ->take(5); // top 5 service

            // Kirim ke view
            return view('frontend.home.index', [
                'services' => $services,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
