<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenieAcs
{
    public static function getDeviceField($cust_id, $field)
    {
        $query = ['VirtualParameters.UsernamePPPoE._value' => $cust_id];
        $queryString = urlencode(json_encode($query));

        $url = "http://69.69.69.2:7557/devices/?query={$queryString}";

        $response = Http::get($url);

        if ($response->failed()) {
            return null;
        }

        $devices = $response->json();
        $device = $devices[0] ?? null;

        return $device['VirtualParameters'][$field]['_value'] ?? null;
    }

    public static function getDeviceSSID($cust_id)
    {
        $query = ['VirtualParameters.UsernamePPPoE._value' => $cust_id];
        $queryString = urlencode(json_encode($query));

        $url = "http://69.69.69.2:7557/devices/?query={$queryString}";

        $response = Http::get($url);

        if ($response->failed()) {
            return null;
        }

        $devices = $response->json();
        $device = $devices[0] ?? null;

        return $device['InternetGatewayDevice']['LANDevice']['1']['WLANConfiguration']['1']['SSID']['_value']
            ?? null;
    }

    public static function getActiveHosts($cust_id)
    {
        $query = ['VirtualParameters.UsernamePPPoE._value' => $cust_id];
        $queryString = urlencode(json_encode($query));

        $url = "http://69.69.69.2:7557/devices/?query={$queryString}";

        $response = Http::get($url);

        if ($response->failed()) {
            return [];
        }

        $devices = $response->json();
        $device = $devices[0] ?? null;

        if (!$device) {
            return [];
        }

        $hosts = $device['InternetGatewayDevice']['LANDevice']['1']['Hosts']['Host'] ?? [];

        $activeHosts = [];

        foreach ($hosts as $id => $host) {
            if (($host['Active']['_value'] ?? false) === true) {
                $activeHosts[] = [
                    'id'           => $id,
                    'hostname'     => $host['HostName']['_value'] ?? null,
                    'ip'           => $host['IPAddress']['_value'] ?? null,
                    'interface'    => $host['InterfaceType']['_value'] ?? null,
                    'mac'          => $host['MACAddress']['_value'] ?? null,
                    'lease_time'   => $host['LeaseTimeRemaining']['_value'] ?? null,
                ];
            }
        }

        return $activeHosts;
    }

    public static function summonHosts($cust_id)
    {
        $query = ['VirtualParameters.UsernamePPPoE._value' => $cust_id];
        $queryString = urlencode(json_encode($query));
        $baseUrl = "http://69.69.69.2:7557";

        // 1️⃣ Cari device ID berdasarkan Username PPPoE
        $deviceResponse = Http::get("$baseUrl/devices/?query={$queryString}");
        if ($deviceResponse->failed() || empty($deviceResponse->json())) {
            return false; // device gak ditemukan
        }

        $device = $deviceResponse->json()[0];
        $deviceId = $device['_id'];

        // 2️⃣ Siapkan URL summon
        $summonUrl = "$baseUrl/devices/$deviceId/tasks?connection_request";

        // 3️⃣ Payload buat perintah refreshObject
        $payload = [
            "name" => "refreshObject",
            "objectName" => "InternetGatewayDevice.LANDevice.1.Hosts.Host."
        ];

        // 4️⃣ Kirim request POST ke GenieACS dengan error handling
        try {
            $response = Http::timeout(5)->post($summonUrl, $payload);

            // kalau berhasil, return true
            return !$response->failed();
        } catch (\Exception $e) {
            // kalau gagal (misal timeout, connection refused, dll)
            Log::error("Summon gagal untuk $cust_id: " . $e->getMessage());
            return false;
        }
    }

    public static function getLastInformStatus($cust_id)
    {
        $query = ['VirtualParameters.UsernamePPPoE._value' => $cust_id];
        $queryString = urlencode(json_encode($query));

        $url = "http://69.69.69.2:7557/devices/?query={$queryString}";

        $response = Http::get($url);

        if ($response->failed()) {
            return [
                'last_inform' => null,
                'status'      => 'No Data',
            ];
        }

        $devices = $response->json();
        $device = $devices[0] ?? null;

        if (!$device || !isset($device['_lastInform'])) {
            return [
                'last_inform' => null,
                'status'      => 'No Data',
            ];
        }

        // ✅ parse UTC, lalu convert ke WIB (Asia/Jakarta)
        $lastInform = \Carbon\Carbon::parse($device['_lastInform'])
            ->setTimezone('Asia/Jakarta');

        $diffMinutes = $lastInform->diffInMinutes(now('Asia/Jakarta'));

        $status = 'Online';
        if ($diffMinutes > (24 * 60)) {
            $status = '> 24 jam (Offline)';
        } elseif ($diffMinutes > 60) {
            $status = '> 1 jam (Offline)';
        } elseif ($diffMinutes > 5) {
            $status = '> 5 menit (Offline)';
        }

        return [
            'last_inform' => $lastInform->format('Y-m-d H:i:s'),
            'status'      => $status,
        ];
    }
}
