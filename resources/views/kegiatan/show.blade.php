@extends('layouts.app')

@section('page-title', 'Detail Kegiatan')

@section('content')
<div class="mb-4 section-header">
    <h4>Detail Kegiatan</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}">Data Kegiatan</a></li>
            <li class="breadcrumb-item active">{{ $kegiatan->nama_kegiatan }}</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="glass-card stagger-1">
            <div class="card-body p-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;border-radius:18px;background:linear-gradient(135deg,var(--accent2),#8b5cf6);box-shadow:0 8px 25px var(--accent2-glow);">
                    <i class="bi bi-calendar-event-fill text-white fs-2"></i>
                </div>
                <h5 class="fw-bold mb-3" style="color:var(--text-primary);">{{ $kegiatan->nama_kegiatan }}</h5>
                <table class="table table-borderless mb-0 text-start" style="font-size:0.85rem;">
                    <tr><td style="color:var(--text-muted);width:100px;">Tanggal</td><td class="fw-semibold">{{ $kegiatan->tanggal_kegiatan->translatedFormat('d F Y') }}</td></tr>
                    <tr><td style="color:var(--text-muted);">Lokasi</td><td class="fw-semibold">{{ $kegiatan->lokasi }}</td></tr>
                    <tr><td style="color:var(--text-muted);">Sasaran RW</td><td class="fw-semibold">{{ $kegiatan->rw_label }}</td></tr>
                    <tr><td style="color:var(--text-muted);">Keterangan</td><td class="fw-semibold">{{ $kegiatan->keterangan ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="glass-card stagger-2">
            <div class="card-header glass-header">
                <h6><i class="bi bi-people me-2" style="color:var(--accent);"></i>Daftar Kehadiran</h6>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>No</th><th>Nama Lansia</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($kegiatan->kehadirans as $i => $kehadiran)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $kehadiran->lansia->nama }}</td>
                                    <td>
                                        @if($kehadiran->status === 'Hadir')
                                            <span class="badge-glass badge-success-glow"><i class="bi bi-check-circle me-1"></i>Hadir</span>
                                        @else
                                            <span class="badge-glass badge-danger-glow"><i class="bi bi-x-circle me-1"></i>Tidak Hadir</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3"><div class="empty-state"><i class="bi bi-clipboard2"></i><p>Belum ada data kehadiran</p></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('kegiatan.index') }}" class="btn-glass"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
@endsection
