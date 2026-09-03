@extends('layouts.app')

@section('page-title', 'Detail Lansia')

@section('content')
<div class="mb-4 section-header">
    <h4>Detail Lansia</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('lansia.index') }}">Data Lansia</a></li>
            <li class="breadcrumb-item active">{{ $lansia->nama }}</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="glass-card stagger-1">
            <div class="card-body text-center p-4">
                <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;border-radius:18px;background:linear-gradient(135deg,var(--accent),var(--accent2));box-shadow:0 8px 25px var(--accent-glow);">
                    <i class="bi bi-person-fill text-white fs-2"></i>
                </div>
                <h5 class="fw-bold mb-1" style="color:var(--text-primary);">{{ $lansia->nama }}</h5>
                <p style="color:var(--text-muted);font-size:0.85rem;" class="mb-1">Kampung {{ $lansia->kampung ?? '-' }}</p>
                <p style="color:var(--text-muted);font-size:0.85rem;" class="mb-3">RW {{ $lansia->rw }}</p>
                <span class="badge-glass badge-{{ $lansia->badge_keaktifan === 'success' ? 'success' : ($lansia->badge_keaktifan === 'primary' ? 'primary' : ($lansia->badge_keaktifan === 'warning' ? 'warning' : 'danger')) }}-glow" style="font-size:0.85rem;padding:0.5rem 1rem;">
                    {{ $lansia->persentase_keaktifan }}% &middot; {{ $lansia->kategori_keaktifan }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="glass-card stagger-2 mb-4">
            <div class="card-header glass-header">
                <h6><i class="bi bi-info-circle me-2" style="color:var(--accent2);"></i>Informasi Pribadi</h6>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless mb-0">
                    <tr><td style="width:160px;color:var(--text-muted);font-size:0.85rem;">NIK</td><td class="fw-semibold">
                        @if($lansia->nik)
                            <span class="nik-mono">{{ $lansia->nik }}</span>
                        @else
                            <span style="color:var(--text-muted);">-</span>
                        @endif
                    </td></tr>
                    <tr><td style="color:var(--text-muted);font-size:0.85rem;">Kampung</td><td class="fw-semibold">{{ $lansia->kampung ?? '-' }}</td></tr>
                    <tr><td style="color:var(--text-muted);font-size:0.85rem;">RW</td><td class="fw-semibold">{{ $lansia->rw }}</td></tr>
                    <tr><td style="color:var(--text-muted);font-size:0.85rem;">Jenis Kelamin</td><td class="fw-semibold">{{ $lansia->jenis_kelamin }}</td></tr>
                    <tr><td style="color:var(--text-muted);font-size:0.85rem;">Alamat</td><td class="fw-semibold">{{ $lansia->alamat }}</td></tr>
                </table>
            </div>
        </div>

        <div class="glass-card stagger-3 mb-4">
            <div class="card-header glass-header d-flex justify-content-between align-items-center">
                <h6><i class="bi bi-heart-pulse me-2" style="color:#ef4444;"></i>Rekam Kesehatan Terbaru</h6>
                <a href="{{ route('health-records.create', ['lansia_id' => $lansia->id]) }}" class="btn-glass btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Rekam
                </a>
            </div>
            <div class="card-body p-4">
                @if($lansia->healthRecords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tekanan Darah</th>
                                    <th>Gula Darah</th>
                                    <th>BMI</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lansia->healthRecords->take(5) as $record)
                                <tr>
                                    <td>
                                        <div style="color: var(--text-primary); font-weight: 500;">
                                            {{ $record->tanggal_pemeriksaan->format('d M Y') }}
                                        </div>
                                        <small style="color: var(--text-muted);">{{ $record->tanggal_pemeriksaan->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($record->tekanan_darah)
                                            <div class="fw-semibold" style="color: var(--text-primary);">{{ $record->tekanan_darah }} mmHg</div>
                                            @if($record->tekanan_darah_status)
                                                <small class="badge-glass 
                                                    @if($record->tekanan_darah_status == 'Normal') badge-success-glow
                                                    @elseif(in_array($record->tekanan_darah_status, ['Elevated', 'Hipertensi Stage 1'])) badge-warning-glow
                                                    @else badge-danger-glow
                                                    @endif" style="font-size: 0.65rem;">
                                                    {{ $record->tekanan_darah_status }}
                                                </small>
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
                                        @if($record->bmi)
                                            <div class="fw-semibold" style="color: var(--text-primary);">{{ $record->bmi }}</div>
                                            <small class="badge-glass 
                                                @if($record->bmi_kategori == 'Normal') badge-success-glow
                                                @elseif($record->bmi_kategori == 'Kurus') badge-warning-glow
                                                @else badge-danger-glow
                                                @endif" style="font-size: 0.65rem;">
                                                {{ $record->bmi_kategori }}
                                            </small>
                                        @else
                                            <span style="color: var(--text-muted);">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('health-records.show', $record) }}" class="btn-action view" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($lansia->healthRecords->count() > 5)
                        <div class="mt-3 text-center">
                            <a href="{{ route('health-records.index', ['lansia_id' => $lansia->id]) }}" class="btn-glass btn-sm">
                                Lihat Semua Rekam Kesehatan ({{ $lansia->healthRecords->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-heart-pulse" style="font-size: 2rem; opacity: 0.3;"></i>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.5rem;">
                            Belum ada rekam kesehatan. 
                            <a href="{{ route('health-records.create', ['lansia_id' => $lansia->id]) }}" style="color: var(--accent);">Tambah sekarang</a>
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="glass-card stagger-4">
            <div class="card-header glass-header">
                <h6><i class="bi bi-clock-history me-2" style="color:var(--accent);"></i>Riwayat Kehadiran</h6>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr><th>No</th><th>Kegiatan</th><th>Tanggal</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($lansia->kehadirans as $i => $kehadiran)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $kehadiran->kegiatan->nama_kegiatan }}</td>
                                    <td>{{ $kehadiran->kegiatan->tanggal_kegiatan->translatedFormat('d M Y') }}</td>
                                    <td>
                                        @if($kehadiran->status === 'Hadir')
                                            <span class="badge-glass badge-success-glow"><i class="bi bi-check-circle me-1"></i>Hadir</span>
                                        @else
                                            <span class="badge-glass badge-danger-glow"><i class="bi bi-x-circle me-1"></i>Tidak Hadir</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4"><div class="empty-state"><i class="bi bi-clock-history"></i><p>Belum ada riwayat kehadiran</p></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('lansia.index') }}" class="btn-glass"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
@endsection
