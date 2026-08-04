<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use App\Models\Lansia;
use Illuminate\Http\Request;

class EmergencyContactController extends Controller
{
    public function index(Request $request)
    {
        $query = EmergencyContact::with('lansia');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_kontak', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%")
                  ->orWhereHas('lansia', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('rw')) {
            $query->whereHas('lansia', function ($q) use ($request) {
                $q->where('rw', $request->rw);
            });
        }

        $contacts = $query->orderBy('is_primary', 'desc')->paginate(20);
        $daftarRw = Lansia::select('rw')
        ->distinct()
        ->orderBy('rw')
        ->pluck('rw');

        return view('emergency-contacts.index', compact('contacts', 'daftarRw'));
    }

    public function create()
    {
        $lansiaList = Lansia::orderBy('nama')->get();
        return view('emergency-contacts.create', compact('lansiaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lansia_id' => 'required|exists:lansias,id',
            'nama_kontak' => 'required|string|max:255',
            'hubungan' => 'required|in:anak,cucu,pasangan,saudara,lainnya',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'is_primary' => 'nullable|boolean'
        ]);

        $validated['is_primary'] = $request->has('is_primary');

        if ($validated['is_primary']) {
            EmergencyContact::where('lansia_id', $validated['lansia_id'])
                ->update(['is_primary' => false]);
        }

        EmergencyContact::create($validated);

        if ($request->has('redirect_to_lansia')) {
            return redirect()->route('lansia.show', $validated['lansia_id'])
                ->with('success', 'Kontak darurat berhasil ditambahkan.');
        }

        return redirect()->route('emergency-contacts.index')
            ->with('success', 'Kontak darurat berhasil ditambahkan.');
    }

    public function show(EmergencyContact $emergencyContact)
    {
        $emergencyContact->load('lansia');
        return view('emergency-contacts.show', compact('emergencyContact'));
    }

    public function edit(EmergencyContact $emergencyContact)
    {
        $lansiaList = Lansia::orderBy('nama')->get();
        return view('emergency-contacts.edit', compact('emergencyContact', 'lansiaList'));
    }

    public function update(Request $request, EmergencyContact $emergencyContact)
    {
        $validated = $request->validate([
            'lansia_id' => 'required|exists:lansias,id',
            'nama_kontak' => 'required|string|max:255',
            'hubungan' => 'required|in:anak,cucu,pasangan,saudara,lainnya',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'is_primary' => 'nullable|boolean'
        ]);

        $validated['is_primary'] = $request->has('is_primary');

        if ($validated['is_primary'] && $emergencyContact->lansia_id == $validated['lansia_id']) {
            EmergencyContact::where('lansia_id', $validated['lansia_id'])
                ->where('id', '!=', $emergencyContact->id)
                ->update(['is_primary' => false]);
        }

        $emergencyContact->update($validated);

        if ($request->has('redirect_to_lansia')) {
            return redirect()->route('lansia.show', $validated['lansia_id'])
                ->with('success', 'Kontak darurat berhasil diperbarui.');
        }

        return redirect()->route('emergency-contacts.index')
            ->with('success', 'Kontak darurat berhasil diperbarui.');
    }

    public function destroy(EmergencyContact $emergencyContact)
    {
        $lansiaId = $emergencyContact->lansia_id;
        $emergencyContact->delete();

        if (request()->has('redirect_to_lansia')) {
            return redirect()->route('lansia.show', $lansiaId)
                ->with('success', 'Kontak darurat berhasil dihapus.');
        }

        return redirect()->route('emergency-contacts.index')
            ->with('success', 'Kontak darurat berhasil dihapus.');
    }
}
