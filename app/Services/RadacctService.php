<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RadacctService
{
    public static function getUsage($username, $tahun = null, $bulan = null, $hari = null)
    {
        $query = DB::connection('radius')->table('radacct')
            ->selectRaw("
            SUM(AcctInputOctets) as totalUpload,
            SUM(AcctOutputOctets) as totalDownload,
            SUM(AcctSessionTime) as totalUptime
        ")
            ->where('Username', $username);

        // Filter Tahun
        if ($tahun) {
            $query->whereYear('AcctStartTime', $tahun);
        }

        // Filter Bulan
        if ($bulan) {
            $query->whereMonth('AcctStartTime', $bulan);
        }

        // Filter Hari
        if ($hari) {
            $query->whereDay('AcctStartTime', $hari);
        }

        $data = $query->first();

        return [
            'upload'   => self::toXByte($data->totalUpload ?? 0),
            'download' => self::toXByte($data->totalDownload ?? 0),
            'total'    => self::toXByte(($data->totalUpload ?? 0) + ($data->totalDownload ?? 0)),
            'uptime'   => self::formatUptime($data->totalUptime ?? 0),
        ];
    }

    private static function formatUptime($seconds)
    {
        $days    = floor($seconds / 86400);
        $hours   = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        $parts = [];
        if ($days > 0) $parts[] = "{$days} Hari";
        if ($hours > 0) $parts[] = "{$hours} Jam";
        if ($minutes > 0) $parts[] = "{$minutes} Menit";

        return implode(" ", $parts);
    }

    public static function toXByte($bytes)
    {
        if ($bytes <= 0) return "0 B";
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), 2) . " " . $units[$i];
    }

    public static function getAvailableDates($username)
    {
        $dates = DB::connection('radius')
            ->table('radacct')
            ->selectRaw("DATE(AcctStartTime) as tanggal")
            ->where('Username', $username)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->pluck('tanggal')
            ->toArray();

        // Pecah jadi array tahun → bulan → hari
        $result = [];
        foreach ($dates as $date) {
            $tahun = date("Y", strtotime($date));
            $bulan = date("n", strtotime($date)); // 1-12
            $hari  = date("j", strtotime($date)); // 1-31

            $result[$tahun][$bulan][] = $hari;
        }

        return $result;
    }
}
