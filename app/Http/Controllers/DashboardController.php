<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kehadiran;
use App\Models\Lansia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $rw = $request->rw;

        // Query dasar
        $lansiaQuery = Lansia::query();

        if ($rw) {
            $lansiaQuery->where('rw', $rw);
        }

        $totalLansia = (clone $lansiaQuery)->count();
        $totalKegiatan = Kegiatan::count();

        $lansiaIds = (clone $lansiaQuery)->pluck('id');

        $totalKehadiran = Kehadiran::whereIn('lansia_id', $lansiaIds)
            ->where('status', 'Hadir')
            ->count();

        $lansias = (clone $lansiaQuery)->get();

        $rataKeaktifan = $totalLansia > 0
            ? round($lansias->avg('persentase_keaktifan'), 2)
            : 0;

        $topLansia = $lansias
            ->sortByDesc('persentase_keaktifan')
            ->take(5);

        $daftarRw = Lansia::select('rw')
            ->distinct()
            ->orderBy('rw')
            ->pluck('rw');

        $chartData = $this->getChartData($rw);
        $pieData = $this->getPieData($rw);

        return view('dashboard', compact(
            'totalLansia',
            'totalKegiatan',
            'totalKehadiran',
            'rataKeaktifan',
            'chartData',
            'pieData',
            'topLansia',
            'daftarRw'
        ));
    }

    private function getChartData($rw = null): array
    {
        $year = now()->year;
        $months = [];
        $data = [];

        $namaBulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        for ($m = 1; $m <= 12; $m++) {
            $months[] = $namaBulan[$m];

            $kegiatanBulanIni = Kegiatan::whereYear('tanggal_kegiatan', $year)
                ->whereMonth('tanggal_kegiatan', $m)
                ->get();

            if ($kegiatanBulanIni->isEmpty()) {
                $data[] = 0;
                continue;
            }

            $lansiaQuery = Lansia::query();

            if ($rw) {
                $lansiaQuery->where('rw', $rw);
            }

            $lansias = $lansiaQuery->get();
            $totalLansia = $lansias->count();
            
            if ($totalLansia === 0) {
                $data[] = 0;
                continue;
            }

            $maxHadir = 0;
            $kegiatanIds = [];
            foreach ($kegiatanBulanIni as $keg) {
                $kegiatanIds[] = $keg->id;
                $kegRws = $keg->rw_array;
                
                foreach ($lansias as $l) {
                    if (empty($kegRws) || in_array($l->rw, $kegRws)) {
                        $maxHadir++;
                    }
                }
            }

            $totalHadir = Kehadiran::whereIn('kegiatan_id', $kegiatanIds)
                ->whereIn('lansia_id', $lansias->pluck('id'))
                ->where('status', 'Hadir')
                ->count();
            $data[] = $maxHadir > 0 ? round(($totalHadir / $maxHadir) * 100, 2) : 0;
        }

        return [
            'labels' => $months,
            'data' => $data,
        ];
    }

    private function getPieData($rw = null): array
    {
        $query = Lansia::query();

        if ($rw) {
            $query->where('rw', $rw);
        }

        $lansias = $query->get();
        $kategori = [
            'Sangat Aktif' => 0,
            'Aktif' => 0,
            'Cukup Aktif' => 0,
            'Kurang Aktif' => 0,
        ];

        foreach ($lansias as $lansia) {
            $kategori[$lansia->kategori_keaktifan]++;
        }

        return [
            'labels' => array_keys($kategori),
            'data' => array_values($kategori),
        ];
    }
}
