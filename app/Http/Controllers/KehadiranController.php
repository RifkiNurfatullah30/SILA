<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kehadiran;
use App\Models\Lansia;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $kegiatans = Kegiatan::orderBy('tanggal_kegiatan', 'desc')->get();
        $selectedKegiatan = null;
        $lansias = collect();
        $kehadiranMap = [];

        $daftarRw = Lansia::select('rw')
                ->distinct()
                ->orderBy('rw')
                ->pluck('rw');

        if ($request->filled('kegiatan_id')) {
            $selectedKegiatan = Kegiatan::with('rwList')->findOrFail($request->kegiatan_id);
            $query = Lansia::query();

            // Jika kegiatan ini punya target RW khusus, batasi hanya RW tersebut
            $targetedRws = $selectedKegiatan->rw_array;
            if (!empty($targetedRws)) {
                $query->whereIn('rw', $targetedRws);
            }

            if ($request->filled('rw')) {
                // Pastikan filter dropdown tidak keluar dari target RW kegiatan
                if (empty($targetedRws) || in_array($request->rw, $targetedRws)) {
                    $query->where('rw', $request->rw);
                } else {
                    // Filter RW tidak valid (di luar target kegiatan), return kosong
                    $query->where('id', 0);
                }
            }

            $lansias = $query->orderBy('nama')->get();

            $kehadiranMap = Kehadiran::where('kegiatan_id', $selectedKegiatan->id)
                ->pluck('status', 'lansia_id')
                ->toArray();
        }

        return view('kehadiran.index', compact('kegiatans', 'selectedKegiatan', 'lansias', 'kehadiranMap', 'daftarRw'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan_id' => ['required', 'exists:kegiatans,id'],
            'kehadiran' => ['required', 'array'],
            'kehadiran.*' => ['in:Hadir,Tidak Hadir'],
        ]);

        $kegiatanId = $request->kegiatan_id;

        foreach ($request->kehadiran as $lansiaId => $status) {
            Kehadiran::updateOrCreate(
                [
                    'lansia_id' => $lansiaId,
                    'kegiatan_id' => $kegiatanId,
                ],
                [
                    'status' => $status,
                ]
            );
        }

        return redirect()->route('kehadiran.index', ['kegiatan_id' => $kegiatanId])
            ->with('success', 'Data kehadiran berhasil disimpan.');
    }
}
