<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use App\Models\Lansia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = HealthRecord::with(['lansia', 'pemeriksa']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('lansia', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('rw')) {
            $query->whereHas('lansia', function ($q) use ($request) {
                $q->where('rw', $request->rw);
            });
        }

        if ($request->filled('dari_tanggal')) {
            $query->where('tanggal_pemeriksaan', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->where('tanggal_pemeriksaan', '<=', $request->sampai_tanggal);
        }

        $records = $query->orderBy('tanggal_pemeriksaan', 'desc')->paginate(15);
        $daftarRw = Lansia::select('rw')
        ->distinct()
        ->orderBy('rw')
        ->pluck('rw');

    return view('health-records.index', compact('records', 'daftarRw'));
    }

    public function create()
    {
    $lansiaList = Lansia::orderBy('nama')->get();

    $healthRecord = null;

    return view('health-records.create', compact(
        'lansiaList',
        'healthRecord'
    ));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lansia_id' => 'required|exists:lansias,id',
            'tanggal_pemeriksaan' => 'required|date',
            'berat_badan' => 'nullable|numeric|min:0|max:300',
            'tinggi_badan' => 'nullable|numeric|min:0|max:250',
            'tekanan_darah_sistolik' => 'nullable|numeric|min:0|max:300',
            'tekanan_darah_diastolik' => 'nullable|numeric|min:0|max:200',
            'gula_darah' => 'nullable|numeric|min:0|max:1000',
            'kolesterol' => 'nullable|numeric|min:0|max:1000',
            'asam_urat' => 'nullable|numeric|min:0|max:100',
            'keluhan' => 'nullable|string',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'obat_diberikan' => 'nullable|string',
            'catatan' => 'nullable|string'
        ]);

        $validated['pemeriksa_id'] = Auth::id();

        HealthRecord::create($validated);

        return redirect()->route('health-records.index')
            ->with('success', 'Rekam kesehatan berhasil ditambahkan.');
    }

    public function show(HealthRecord $healthRecord)
    {
        $healthRecord->load(['lansia', 'pemeriksa']);
        return view('health-records.show', compact('healthRecord'));
    }

    public function edit(HealthRecord $healthRecord)
    {
        $lansiaList = Lansia::orderBy('nama')->get();
        return view('health-records.edit', compact('healthRecord', 'lansiaList'));
    }

    public function update(Request $request, HealthRecord $healthRecord)
    {
        $validated = $request->validate([
            'lansia_id' => 'required|exists:lansias,id',
            'tanggal_pemeriksaan' => 'required|date',
            'berat_badan' => 'nullable|numeric|min:0|max:300',
            'tinggi_badan' => 'nullable|numeric|min:0|max:250',
            'tekanan_darah_sistolik' => 'nullable|numeric|min:0|max:300',
            'tekanan_darah_diastolik' => 'nullable|numeric|min:0|max:200',
            'gula_darah' => 'nullable|numeric|min:0|max:1000',
            'kolesterol' => 'nullable|numeric|min:0|max:1000',
            'asam_urat' => 'nullable|numeric|min:0|max:100',
            'keluhan' => 'nullable|string',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'obat_diberikan' => 'nullable|string',
            'catatan' => 'nullable|string'
        ]);

        $healthRecord->update($validated);

        return redirect()->route('health-records.index')
            ->with('success', 'Rekam kesehatan berhasil diperbarui.');
    }

    public function destroy(HealthRecord $healthRecord)
    {
        $healthRecord->delete();

        return redirect()->route('health-records.index')
            ->with('success', 'Rekam kesehatan berhasil dihapus.');
    }
}
