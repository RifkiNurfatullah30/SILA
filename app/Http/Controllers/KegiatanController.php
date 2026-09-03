<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Lansia;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kegiatan::with('rwList');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('rw')) {
            $rw = $request->rw;
            $query->whereHas('rwList', function($q) use ($rw) {
                $q->where('rw', $rw);
            });
        }

        $kegiatans = $query->orderBy('tanggal_kegiatan', 'desc')->paginate(10)->withQueryString();
        
        $daftarRw = Lansia::select('rw')->distinct()->orderBy('rw')->pluck('rw');

        return view('kegiatan.index', compact('kegiatans', 'daftarRw'));
    }

    public function create()
    {
        $daftarRw = Lansia::select('rw')->distinct()->orderBy('rw')->pluck('rw');
        return view('kegiatan.create', compact('daftarRw'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal_kegiatan' => ['required', 'date'],
            'lokasi' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'rws' => ['nullable', 'array'],
            'rws.*' => ['string'],
        ]);

        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
            'lokasi' => $validated['lokasi'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        if (!empty($validated['rws'])) {
            foreach ($validated['rws'] as $rw) {
                $kegiatan->rwList()->create(['rw' => $rw]);
            }
        }

        return redirect()->route('kegiatan.index')
            ->with('success', 'Data kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load(['kehadirans.lansia', 'rwList']);
        return view('kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        $kegiatan->load('rwList');
        $daftarRw = Lansia::select('rw')->distinct()->orderBy('rw')->pluck('rw');
        return view('kegiatan.edit', compact('kegiatan', 'daftarRw'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal_kegiatan' => ['required', 'date'],
            'lokasi' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'rws' => ['nullable', 'array'],
            'rws.*' => ['string'],
        ]);

        $kegiatan->update([
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
            'lokasi' => $validated['lokasi'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        $kegiatan->rwList()->delete();
        if (!empty($validated['rws'])) {
            foreach ($validated['rws'] as $rw) {
                $kegiatan->rwList()->create(['rw' => $rw]);
            }
        }

        return redirect()->route('kegiatan.index')
            ->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('kegiatan.index')
            ->with('success', 'Data kegiatan berhasil dihapus.');
    }
}
