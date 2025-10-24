<?php

namespace App\Http\Controllers;

use App\Helpers\GeoHelper;
use App\Services\GenieAcs;
use App\Services\RadacctService;
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
    public function index(Request $request)
    {
        // Default tampil 1 jam terakhir
        $services = $this->fetchTopServices('h');
        $user = auth()->user();

        // ambil IP dari radius
        $ipAddress = \App\Models\RadReply::where('username', $user->cust_id)
            ->where('attribute', 'Framed-IP-Address')
            ->value('value');

        $group = \App\Models\RadUserGroup::where('username', $user->cust_id)
            ->value('groupname');

        // ambil filter dari GET
        $tahun = $request->input('tahun');   // contoh: 2025
        $bulan = $request->input('bulan');   // contoh: 9
        $hari  = $request->input('hari');    // contoh: 6

        // ambil usage & tanggal yg tersedia
        $usage = RadacctService::getUsage($user->cust_id, $tahun, $bulan, $hari);
        $availableDates = RadacctService::getAvailableDates($user->cust_id);

        $temperature = GenieAcs::getDeviceField($user->cust_id, 'Temperature');
        $rxPower     = GenieAcs::getDeviceField($user->cust_id, 'RXPower');
        $uptime      = GenieAcs::getDeviceField($user->cust_id, 'Uptime');
        $pppIp       = GenieAcs::getDeviceField($user->cust_id, 'PPPIP');
        $ssid = GenieAcs::getDeviceSSID($user->cust_id);
        $activeHosts = GenieAcs::getActiveHosts($user->cust_id);
        $deviceStatus = GenieAcs::getLastInformStatus($user->cust_id);

        return view('home.index', [
            'title' => 'Dashboard - Hyperlink',
            'services' => $services,
            'user'  => $user,
            'ip'    => $ipAddress,
            'group' => $group,
            'usage' => $usage,
            'availableDates' => $availableDates,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'hari'  => $hari,
            'temperature' => $temperature,
            'rxPower'     => $rxPower,
            'uptime'      => $uptime,
            'pppIp'       => $pppIp,
            'ssid'        => $ssid,
            'activeHosts' => $activeHosts,
            'countActiveHosts' => count($activeHosts),
            'deviceStatus' => $deviceStatus,
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

    public function getActiveHosts()
    {
        $user = auth()->user();

        // 1️⃣ Panggil summon agar ONT update ke GenieACS
        GenieAcs::summonHosts($user->cust_id);

        // 2️⃣ Polling (cek berulang sampai data baru muncul)
        $hosts = [];
        for ($i = 0; $i < 5; $i++) { // 5 kali percobaan (sekitar 5 detik total)
            sleep(1); // tunggu 1 detik tiap loop
            $hosts = GenieAcs::getActiveHosts($user->cust_id);
            if (count($hosts) > 0) break; // kalau udah ada data, stop
        }

        return response()->json([
            'hosts' => $hosts,
            'count' => count($hosts)
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
