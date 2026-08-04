@extends('layouts.app')

@section('title', 'Rekam Kesehatan')

@section('page-title', 'Rekam Kesehatan')

@section('content')
<div class="glass-card mb-3 stagger-1">
    <div class="p-3">
        <form method="GET" action="{{ route('health-records.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari Lansia</label>
                    <input type="text" name="search" class="form-control" 
                           value="{{ request('search') }}" placeholder="Nama atau NIK...">
                </div>
                <div class="col-md-3">
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
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" class="form-control" value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control" value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn-accent w-100 justify-content-center">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('health-records.index') }}" class="btn-glass w-100 justify-content-center">
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
            <h6 class="m-0"><i class="bi bi-heart-pulse me-2"></i>Rekam Kesehatan</h6>
            <span class="badge-glass badge-primary-glow">{{ $records->total() }} data</span>
        </div>
        <a href="{{ route('health-records.create') }}" class="btn-accent">
            <i class="bi bi-plus-circle me-1"></i> Tambah Rekam
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Lansia</th>
                    <th>Tanggal</th>
                    <th>Tekanan Darah</th>
                    <th>Gula Darah</th>
                    <th>BB/TB</th>
                    <th>BMI</th>
                    <th>Pemeriksa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr>
                    <td>
                        <div class="fw-semibold" style="color: var(--text-primary);">
                            {{ $record->lansia->nama }}
                        </div>
                        <div class="nik-mono">{{ $record->lansia->nik }}</div>
                    </td>
                    <td>
                        <div style="color: var(--text-primary);">
                            {{ $record->tanggal_pemeriksaan->format('d M Y') }}
                        </div>
                        <small style="color: var(--text-muted);">
                            {{ $record->tanggal_pemeriksaan->diffForHumans() }}
                        </small>
                    </td>
                    <td>
                        @if($record->tekanan_darah)
                            <div class="fw-semibold" style="color: var(--text-primary);">
                                {{ $record->tekanan_darah }} mmHg
                            </div>
                            @if($record->tekanan_darah_status)
                                <span class="badge-glass 
                                    @if($record->tekanan_darah_status == 'Normal') badge-success-glow
                                    @elseif(in_array($record->tekanan_darah_status, ['Elevated', 'Hipertensi Stage 1'])) badge-warning-glow
                                    @else badge-danger-glow
                                    @endif" style="font-size: 0.7rem;">
                                    {{ $record->tekanan_darah_status }}
                                </span>
                            @endif
                        @else
                            <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($record->gula_darah)
                            <span style="color: var(--text-primary);">{{ $record->gula_darah }} mg/dL</span>
                        @else
                            <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($record->berat_badan || $record->tinggi_badan)
                            <span style="color: var(--text-primary);">
                                {{ $record->berat_badan ?? '-' }} kg / {{ $record->tinggi_badan ?? '-' }} cm
                            </span>
                        @else
                            <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($record->bmi)
                            <div class="fw-semibold" style="color: var(--text-primary);">{{ $record->bmi }}</div>
                            <span class="badge-glass 
                                @if($record->bmi_kategori == 'Normal') badge-success-glow
                                @elseif($record->bmi_kategori == 'Kurus') badge-warning-glow
                                @else badge-danger-glow
                                @endif" style="font-size: 0.7rem;">
                                {{ $record->bmi_kategori }}
                            </span>
                        @else
                            <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($record->pemeriksa)
                            <span style="color: var(--text-secondary); font-size: 0.85rem;">
                                {{ $record->pemeriksa->name }}
                            </span>
                        @else
                            <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('health-records.show', $record) }}" class="btn-action view" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('health-records.edit', $record) }}" class="btn-action edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('health-records.destroy', $record) }}" method="POST" 
                                  onsubmit="return confirm('Hapus rekam kesehatan ini?')">
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
                    <td colspan="8">
                        <div class="text-center py-5">
                            <div style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;">
                                <i class="bi bi-heart-pulse"></i>
                            </div>
                            <h6 style="color: var(--text-muted);">Belum ada rekam kesehatan</h6>
                            <p style="color: var(--text-muted); font-size: 0.85rem;">
                                {{ request()->hasAny(['search', 'lansia_id', 'dari_tanggal', 'sampai_tanggal'])
                                    ? 'Tidak ada hasil yang cocok dengan filter pencarian.'
                                    : 'Mulai tambahkan rekam kesehatan lansia.' }}
                            </p>
                            @if(!request()->hasAny(['search', 'lansia_id', 'dari_tanggal', 'sampai_tanggal']))
                            <a href="{{ route('health-records.create') }}" class="btn-accent">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Rekam Pertama
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($records->hasPages())
    <div class="p-3 d-flex justify-content-end">
        {{ $records->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
