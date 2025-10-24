<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function download(Request $request)
    {
        $username = auth()->user()->cust_id;
        $type = $request->input('type', 'monthly'); // monthly / all

        // Ambil data user
        $user = DB::connection('radius')
            ->table('userinfo as u')
            ->leftJoin('radusergroup as g', 'u.username', '=', 'g.username')
            ->select('u.username', 'u.lastname', 'g.groupname as profile')
            ->where('u.username', $username)
            ->first();

        $fullname = $user->lastname ?: $username;
        $userid   = $user->username;
        $profile  = $user->profile;

        if ($type === 'monthly') {
            // Report harian bulan ini
            $bulan = date("Y-m");
            $dailyReports = DB::connection('radius')
                ->table('radacct')
                ->selectRaw("
                DATE(AcctStartTime) as tanggal,
                MIN(AcctStartTime) as startDay,
                MAX(CASE
                        WHEN AcctStopTime IS NULL
                            OR AcctStopTime='0000-00-00 00:00:00'
                        THEN NOW()
                        ELSE AcctStopTime
                    END) as endDay,
                SUM(AcctInputOctets) as upload,
                SUM(AcctOutputOctets) as download,
                SUM(AcctSessionTime) as uptime
            ")
                ->where('Username', $username)
                ->whereRaw("DATE_FORMAT(AcctStartTime, '%Y-%m') = ?", [$bulan])
                ->groupBy(DB::raw("DATE(AcctStartTime)"))
                ->orderBy('tanggal', 'ASC')
                ->get()
                ->map(function ($row) {
                    $tglRange = date("d/m H:i", strtotime($row->startDay)) . " - " .
                        date("d/m H:i", strtotime($row->endDay));
                    return [
                        'tglRange' => $tglRange,
                        'download' => $this->toXByte($row->download),
                        'upload'   => $this->toXByte($row->upload),
                        'total'    => $this->toXByte($row->download + $row->upload),
                        'uptime'   => $this->formatUptime($row->uptime),
                    ];
                });

            $data = compact('username', 'fullname', 'userid', 'profile', 'type', 'dailyReports');
        } else {
            // Report rekap semua bulan
            $monthlyReports = DB::connection('radius')
                ->table('radacct')
                ->selectRaw("
                DATE_FORMAT(AcctStartTime, '%Y-%m') as bulan,
                SUM(AcctInputOctets) as upload,
                SUM(AcctOutputOctets) as download,
                SUM(AcctSessionTime) as uptime,
                COUNT(DISTINCT DATE(AcctStartTime)) as hari
            ")
                ->where('Username', $username)
                ->groupBy(DB::raw("DATE_FORMAT(AcctStartTime, '%Y-%m')"))
                ->orderBy('bulan', 'ASC')
                ->get()
                ->map(function ($row) {
                    $total = $row->download + $row->upload;
                    $avg   = $row->hari > 0 ? $this->toXByte($total / $row->hari) : "0 B";
                    return [
                        'bulan'    => strtoupper(date("F Y", strtotime($row->bulan . "-01"))),
                        'download' => $this->toXByte($row->download),
                        'upload'   => $this->toXByte($row->upload),
                        'total'    => $this->toXByte($total),
                        'uptime'   => $this->formatUptime($row->uptime),
                        'avg'      => $avg,
                    ];
                });

            $data = compact('username', 'fullname', 'userid', 'profile', 'type', 'monthlyReports');
        }

        // Render ke Blade
        $html = view('reports.bandwidth', $data)->render();

        // Buat PDF
        $pdf = Pdf::loadHTML($html)->setPaper('A4', 'landscape');

        // Nama file
        if ($type === 'all') {
            $filename = "{$fullname}_Summary_Total_" . date("Y") . ".pdf";
        } else {
            $filename = "{$fullname}_Summary_Total_" . date("F_Y") . ".pdf";
        }

        return $pdf->download(preg_replace('/\s+/', '_', $filename));
    }

    // Helpers
    private function toXByte($bytes)
    {
        if ($bytes === null || $bytes == 0) return "0 B";
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . " " . $units[$i];
    }

    private function formatUptime($seconds)
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        $parts = [];
        if ($days > 0) $parts[] = "$days hari";
        if ($hours > 0) $parts[] = "$hours jam";
        if ($minutes > 0) $parts[] = "$minutes menit";
        if ($secs > 0 && $days == 0) $parts[] = "$secs detik";
        return implode(" ", $parts);
    }
}
