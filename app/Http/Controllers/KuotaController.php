<?php

namespace App\Http\Controllers;

use App\Services\RadacctService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KuotaController extends Controller
{
    /**
     * Ambil list paket langsung dari RADIUS (misalnya dari tabel radgroupreply atau radgroupcheck)
     */
    private function getPaketsFromRadius()
    {
        // Contoh ambil dari radgroupreply berdasarkan GroupName unik
        $groups = DB::connection('radius')
            ->table('radgroupreply')
            ->select('groupname')
            ->distinct()
            ->get();

        $pakets = [];

        foreach ($groups as $i => $group) {
            // Ambil parameter penting untuk tiap group
            $attrs = DB::connection('radius')
                ->table('radgroupreply')
                ->where('groupname', $group->groupname)
                ->pluck('value', 'attribute');

            $pakets[] = [
                'id' => $i + 1,
                'nama' => ucfirst($group->groupname),
                'deskripsi' => $attrs['WISPr-Bandwidth-Max-Down'] ?? 'Paket internet',
                'kuota' => $attrs['Max-Daily-Session'] ?? 'Unlimited',
                'kecepatan' => isset($attrs['WISPr-Bandwidth-Max-Down'])
                    ? round($attrs['WISPr-Bandwidth-Max-Down'] / 1000000) . ' Mbps'
                    : 'N/A',
                'masa_aktif' => '30 Hari',
                'harga' => rand(50000, 250000), // Bisa ambil dari tabel paket tersendiri nanti
                'warna' => ['primary', 'success', 'warning', 'danger'][($i % 4)],
                'ikon' => ['bi-wifi', 'bi-lightning-fill', 'bi-star-fill', 'bi-gem'][($i % 4)],
                'badge' => $i == 1 ? 'Popular' : null
            ];
        }

        return $pakets;
    }

    public function index()
    {
        $pakets = $this->getPaketsFromRadius();

        $username = auth()->user()->username ?? 'demo';
        $limitKuotaGB = 50; // default kuota
        $usage = RadacctService::getUsage($username, date('Y'), date('m'));

        $totalUsageGB = isset($usage['total_bytes'])
            ? round($usage['total_bytes'] / pow(1024, 3), 2)
            : 0;

        $persentase = $limitKuotaGB > 0
            ? min(($totalUsageGB / $limitKuotaGB) * 100, 100)
            : 0;

        // Cek paket aktif user
        $activeGroup = DB::connection('radius')
            ->table('radusergroup')
            ->where('username', $username)
            ->value('groupname');

        return view('paket-kuota.index', [
            'title' => 'Paket Kuota Internet',
            'pakets' => $pakets,
            'usage' => $usage,
            'limit' => $limitKuotaGB,
            'persentase' => $persentase,
            'activeGroup' => $activeGroup,
        ]);
    }

    public function pilih($id)
    {
        $pakets = $this->getPaketsFromRadius();
        $paket = collect($pakets)->firstWhere('id', (int)$id);

        if (!$paket) {
            abort(404, 'Paket tidak ditemukan.');
        }

        return view('paket-kuota.pilih', compact('paket'));
    }
}
