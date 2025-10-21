<?php

namespace App\Http\Controllers;

use App\Helpers\GeoHelper;
use ClickHouseDB\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Default tampil 1 jam terakhir
        $services = $this->fetchTopServices('h');

        return view('home.index', [
            'title' => 'Dashboard - Hyperlink',
            'services' => $services,
        ]);
    }

    public function getTopServices(Request $request)
    {
        $range = $request->get('average', 'h');
        $services = $this->fetchTopServices($range);

        return response()->json([
            'success' => true,
            'data' => $services,
        ]);
    }

    private function fetchTopServices($range)
    {
        try {
            $config = [
                'host' => env('CLICKHOUSE_HOST', '69.69.69.2'),
                'port' => env('CLICKHOUSE_PORT', 8123),
                'username' => env('CLICKHOUSE_USERNAME', 'default'),
                'password' => env('CLICKHOUSE_PASSWORD', ''),
            ];

            $client = new Client($config);
            $client->database(env('CLICKHOUSE_DATABASE', 'default'));

            // Tentukan interval waktu
            $intervalMap = [
                'h' => '1 HOUR',
                'd' => '1 DAY',
                'w' => '7 DAY',
                'm' => '30 DAY',
                'y' => '365 DAY',
            ];
            $interval = $intervalMap[$range] ?? '1 HOUR';

            $rows = $client->select("
                SELECT
                    SrcAddr,
                    CONCAT('AS', CAST(SrcAS AS String)) AS asn,
                    SUM(Bytes) AS total_bytes
                FROM flows
                WHERE TimeReceived > now() - INTERVAL $interval
                GROUP BY SrcAddr, SrcAS
                ORDER BY total_bytes DESC
                LIMIT 10
            ")->rows();

            $services = collect($rows)->map(function ($row) {
                $asnInfo = GeoHelper::getAsnInfo($row['SrcAddr']);
                $countryInfo = GeoHelper::getCountryInfo($row['SrcAddr']);

                return [
                    'asn' => $asnInfo['asn'] ?? 'Unknown',
                    'name' => $asnInfo['organization'] ?? 'Unknown',
                    'country' => $countryInfo['country_name'] ?? 'Unknown',
                    'total_bytes' => $row['total_bytes'],
                ];
            })->values()->toArray();

            return $services;
        } catch (\Exception $e) {
            Log::error('ClickHouse query failed: ' . $e->getMessage());
            return [];
        }
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
