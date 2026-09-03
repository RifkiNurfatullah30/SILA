<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kehadiran;
use App\Models\Lansia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $rw = $request->input('rw');

        $data = $this->getLaporanData($bulan, $tahun, $rw);

        $daftarRw = Lansia::select('rw')
            ->distinct()
            ->orderBy('rw')
            ->pluck('rw');

        $tahunList = Kegiatan::selectRaw('CAST(strftime("%Y", tanggal_kegiatan) AS INTEGER) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        if ($tahunList->isEmpty()) {
            $tahunList = collect([now()->year]);
        }

        return view('laporan.index', array_merge($data, [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tahunList' => $tahunList,
            'daftarRw' => $daftarRw,
            'rw' => $rw,
        ]));
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $rw = $request->input('rw');

        $data = $this->getLaporanData($bulan, $tahun, $rw);
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['rw'] = $rw;

        $namaBulan = $this->getNamaBulan($bulan);
        $data['namaBulan'] = $namaBulan;

        $pdf = Pdf::loadView('laporan.pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("laporan-keaktifan-lansia-{$namaBulan}-{$tahun}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $rw = $request->input('rw');

        $data = $this->getLaporanData($bulan, $tahun, $rw);
        $namaBulan = $this->getNamaBulan($bulan);

        $filename = "laporan-keaktifan-lansia-{$namaBulan}-{$tahun}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data, $namaBulan, $tahun) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ["Laporan Keaktifan Lansia - {$namaBulan} {$tahun}"]);
            fputcsv($file, []);
            fputcsv($file, ['No', 'Nama', 'Jenis Kelamin', 'RW', 'Total Kegiatan', 'Total Hadir', 'Persentase (%)', 'Kategori']);

            foreach ($data['lansias'] as $i => $lansia) {
                fputcsv($file, [
                    $i + 1,
                    $lansia->nama,
                    $lansia->jenis_kelamin,
                    'RW ' . $lansia->rw,
                    $lansia->total_kegiatan_valid_bulan,
                    $lansia->total_hadir_bulan,
                    $lansia->persentase_bulan,
                    $lansia->kategori_bulan,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getLaporanData(int $bulan, int $tahun, $rw = null): array
    {
        $kegiatans = Kegiatan::with('rwList')
            ->whereYear('tanggal_kegiatan', $tahun)
            ->whereMonth('tanggal_kegiatan', $bulan)
            ->get();
            
        $kegiatanIds = $kegiatans->pluck('id');

        $query = Lansia::orderBy('nama');

        if (!empty($rw)) {
            $query->where('rw', $rw);
        }

        $lansias = $query->get();

        foreach ($lansias as $lansia) {
            $totalKegiatanValid = 0;
            foreach ($kegiatans as $keg) {
                $kegRws = $keg->rw_array;
                if (empty($kegRws) || in_array($lansia->rw, $kegRws)) {
                    $totalKegiatanValid++;
                }
            }

            $totalHadir = Kehadiran::where('lansia_id', $lansia->id)
                ->whereIn('kegiatan_id', $kegiatanIds)
                ->where('status', 'Hadir')
                ->count();

            $lansia->total_hadir_bulan = $totalHadir;
            $lansia->total_kegiatan_valid_bulan = $totalKegiatanValid;
            $lansia->persentase_bulan = $totalKegiatanValid > 0
                ? round(($totalHadir / $totalKegiatanValid) * 100, 2)
                : 0;
            $lansia->kategori_bulan = match (true) {
                $lansia->persentase_bulan >= 80 => 'Sangat Aktif',
                $lansia->persentase_bulan >= 60 => 'Aktif',
                $lansia->persentase_bulan >= 40 => 'Cukup Aktif',
                default => 'Kurang Aktif',
            };
            $lansia->badge_bulan = match ($lansia->kategori_bulan) {
                'Sangat Aktif' => 'success',
                'Aktif' => 'primary',
                'Cukup Aktif' => 'warning',
                default => 'danger',
            };
        }

        return [
            'lansias' => $lansias,
            'totalKegiatanBulan' => $kegiatans->count(),
        ];
    }

    private function getNamaBulan(int $bulan): string
    {
        $nama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $nama[$bulan] ?? '';
    }
}
