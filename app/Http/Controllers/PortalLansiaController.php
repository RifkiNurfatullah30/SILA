<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalLansiaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $lansia = $user->lansia;

        if (!$lansia) {
            abort(403, 'Anda tidak terdaftar sebagai lansia.');
        }

        $totalKehadiran = $lansia->kehadirans()->where('status', 'Hadir')->count();
        $persentaseKeaktifan = $lansia->persentase_keaktifan;
        $kategoriKeaktifan = $lansia->kategori_keaktifan;
        $badgeKeaktifan = $lansia->badge_keaktifan;

        // Kegiatan mendatang (besok dan seterusnya) untuk lansia ini
        $kegiatanMendatang = Kegiatan::where('tanggal_kegiatan', '>=', today())
            ->where(function ($q) use ($lansia) {
                $q->whereDoesntHave('rwList')
                  ->orWhereHas('rwList', function ($sq) use ($lansia) {
                      $sq->where('rw', $lansia->rw);
                  });
            })
            ->orderBy('tanggal_kegiatan', 'asc')
            ->take(5)
            ->get();

        // Riwayat kehadiran lansia
        $riwayatKehadiran = $lansia->kehadirans()
            ->with('kegiatan')
            ->join('kegiatans', 'kehadirans.kegiatan_id', '=', 'kegiatans.id')
            ->orderBy('kegiatans.tanggal_kegiatan', 'desc')
            ->take(10)
            ->select('kehadirans.*')
            ->get();

        // Data chart kehadiran
        $totalKegiatan = Kegiatan::where(function ($q) use ($lansia) {
            $q->whereDoesntHave('rwList')
              ->orWhereHas('rwList', function ($sq) use ($lansia) {
                  $sq->where('rw', $lansia->rw);
              });
        })->count();
        $totalTidakHadir = $totalKegiatan - $totalKehadiran;
        $chartKehadiran = [
            'labels' => ['Hadir', 'Tidak Hadir'],
            'data' => [$totalKehadiran, max(0, $totalTidakHadir)]
        ];

        return view('portal.lansia', compact(
            'lansia',
            'totalKehadiran',
            'persentaseKeaktifan',
            'kategoriKeaktifan',
            'badgeKeaktifan',
            'kegiatanMendatang',
            'riwayatKehadiran',
            'chartKehadiran'
        ));
    }
}
