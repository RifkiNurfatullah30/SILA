@extends('layouts.app')

@section('title', 'Kontak Darurat')

@section('page-title', 'Kontak Darurat')

@section('content')
<div class="glass-card mb-3 stagger-1">
    <div class="p-3">
        <form method="GET" action="{{ route('emergency-contacts.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Cari Kontak</label>
                    <input type="text" name="search" class="form-control" 
                           value="{{ request('search') }}" placeholder="Nama kontak, telepon...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter RW</label>
                    <select name="rw" class="form-select">
                    <option value="">Semua RW</option>

                    @foreach($daftarRw as $rw)
                        <option value="{{ $rw }}"
                            {{ request('rw') == $rw ? 'selected' : '' }}>
                            RW {{ $rw }}
                        </option>
                    @endforeach
                </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn-accent flex-grow-1 justify-content-center">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                    <a href="{{ route('emergency-contacts.index') }}" class="btn-glass justify-content-center">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="glass-card stagger-2">
    <div class="glass-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <h6 class="m-0"><i class="bi bi-person-lines-fill me-2"></i>Kontak Darurat</h6>
            <span class="badge-glass badge-primary-glow">{{ $contacts->total() }} kontak</span>
        </div>
        <a href="{{ route('emergency-contacts.create') }}" class="btn-accent">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kontak
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Kontak</th>
                    <th>Hubungan</th>
                    <th>No. Telepon</th>
                    <th>Lansia</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr>
                    <td>
                        <div class="fw-semibold" style="color: var(--text-primary);">
                            {{ $contact->nama_kontak }}
                        </div>
                        @if($contact->alamat)
                            <small style="color: var(--text-muted);">{{ Str::limit($contact->alamat, 40) }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge-glass badge-primary-glow">
                            {{ $contact->hubungan_label }}
                        </span>
                    </td>
                    <td>
                        <a href="tel:{{ $contact->nomor_telepon }}" style="color: var(--accent2); text-decoration: none; font-weight: 500;">
                            <i class="bi bi-telephone me-1"></i>{{ $contact->nomor_telepon }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('lansia.show', $contact->lansia_id) }}" style="color: var(--accent); text-decoration: none; font-weight: 500;">
                            <i class="bi bi-person me-1"></i>{{ $contact->lansia->nama }}
                        </a>
                    </td>
                    <td>
                        @if($contact->is_primary)
                            <span class="badge-glass badge-success-glow">
                                <i class="bi bi-star-fill me-1"></i> Utama
                            </span>
                        @else
                            <span class="badge-glass" style="background: var(--input-bg); color: var(--text-muted); border: 1px solid var(--border);">
                                Alternatif
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('emergency-contacts.edit', $contact) }}" class="btn-action edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('emergency-contacts.destroy', $contact) }}" method="POST"
                                  onsubmit="return confirm('Hapus kontak darurat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action delete" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="text-center py-5">
                            <div style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;">
                                <i class="bi bi-person-lines-fill"></i>
                            </div>
                            <h6 style="color: var(--text-muted);">Belum ada kontak darurat</h6>
                            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                                {{ request()->hasAny(['search', 'lansia_id'])
                                    ? 'Tidak ada hasil yang cocok dengan pencarian.'
                                    : 'Tambahkan kontak darurat untuk setiap lansia.' }}
                            </p>
                            @if(!request()->hasAny(['search', 'lansia_id']))
                            <a href="{{ route('emergency-contacts.create') }}" class="btn-accent">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Kontak Pertama
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($contacts->hasPages())
    <div class="p-3 d-flex justify-content-end">
        {{ $contacts->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
