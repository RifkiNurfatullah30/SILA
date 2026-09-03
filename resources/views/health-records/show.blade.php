@extends('layouts.app')

@section('title', 'Detail Rekam Kesehatan')

@section('page-title', 'Detail Rekam Kesehatan')

@section('content')
<div class="glass-card stagger-1">
    <div class="glass-header d-flex justify-content-between align-items-center">
        <h6><i class="bi bi-heart-pulse me-2"></i> Detail Rekam Kesehatan</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('health-records.edit', $healthRecord) }}" class="btn-glass btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('health-records.index') }}" class="btn-glass btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div class="stat-label">Nama Lansia</div>
                        <h5 class="mb-0" style="color: var(--text-primary);">{{ $healthRecord->lansia->nama }}</h5>
                        <small style="color: var(--text-muted);">RW {{ $healthRecord->lansia->rw }}</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <div class="stat-label">Tanggal Pemeriksaan</div>
                        <h5 class="mb-0" style="color: var(--text-primary);">{{ $healthRecord->tanggal_pemeriksaan->format('d F Y') }}</h5>
                        <small style="color: var(--text-muted);">{{ $healthRecord->tanggal_pemeriksaan->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <h6 class="text-uppercase mb-3" style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; letter-spacing: 1px;">
                    <i class="bi bi-activity me-2"></i>Tanda Vital
                </h6>
                <div class="row g-3">
                    @if($healthRecord->berat_badan || $healthRecord->tinggi_badan)
                    <div class="col-md-4">
                        <div class="p-3" style="background: var(--input-bg); border: 1px solid var(--border); border-radius: 12px;">
                            <div class="stat-label mb-1">Berat & Tinggi Badan</div>
                            <div class="d-flex align-items-baseline gap-2">
                                @if($healthRecord->berat_badan)
                                    <span style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $healthRecord->berat_badan }}</span>
                                    <span style="color: var(--text-muted);">kg</span>
                                @endif
                                @if($healthRecord->tinggi_badan)
                                    <span style="color: var(--text-muted);">/</span>
                                    <span style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $healthRecord->tinggi_badan }}</span>
                                    <span style="color: var(--text-muted);">cm</span>
                                @endif
                            </div>
                            @if($healthRecord->bmi)
                                <div class="mt-2">
                                    <span class="badge-glass badge-primary-glow">BMI: {{ $healthRecord->bmi }} - {{ $healthRecord->bmi_kategori }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($healthRecord->tekanan_darah)
                    <div class="col-md-4">
                        <div class="p-3" style="background: var(--input-bg); border: 1px solid var(--border); border-radius: 12px;">
                            <div class="stat-label mb-1">Tekanan Darah</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $healthRecord->tekanan_darah }}</span>
                                <span style="color: var(--text-muted);">mmHg</span>
                            </div>
                            @if($healthRecord->tekanan_darah_status)
                                <div class="mt-2">
                                    <span class="badge-glass 
                                        @if($healthRecord->tekanan_darah_status == 'Normal') badge-success-glow
                                        @elseif(in_array($healthRecord->tekanan_darah_status, ['Elevated', 'Hipertensi Stage 1'])) badge-warning-glow
                                        @else badge-danger-glow
                                        @endif">
                                        {{ $healthRecord->tekanan_darah_status }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($healthRecord->gula_darah)
                    <div class="col-md-4">
                        <div class="p-3" style="background: var(--input-bg); border: 1px solid var(--border); border-radius: 12px;">
                            <div class="stat-label mb-1">Gula Darah</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $healthRecord->gula_darah }}</span>
                                <span style="color: var(--text-muted);">mg/dL</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($healthRecord->kolesterol)
                    <div class="col-md-4">
                        <div class="p-3" style="background: var(--input-bg); border: 1px solid var(--border); border-radius: 12px;">
                            <div class="stat-label mb-1">Kolesterol</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $healthRecord->kolesterol }}</span>
                                <span style="color: var(--text-muted);">mg/dL</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($healthRecord->asam_urat)
                    <div class="col-md-4">
                        <div class="p-3" style="background: var(--input-bg); border: 1px solid var(--border); border-radius: 12px;">
                            <div class="stat-label mb-1">Asam Urat</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $healthRecord->asam_urat }}</span>
                                <span style="color: var(--text-muted);">mg/dL</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($healthRecord->keluhan || $healthRecord->diagnosa || $healthRecord->tindakan || $healthRecord->obat_diberikan || $healthRecord->catatan)
            <div class="col-12 mt-4">
                <h6 class="text-uppercase mb-3" style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; letter-spacing: 1px;">
                    <i class="bi bi-clipboard2-pulse me-2"></i>Pemeriksaan Klinis
                </h6>
                <div class="row g-3">
                    @if($healthRecord->keluhan)
                    <div class="col-12">
                        <div class="p-3" style="background: var(--input-bg); border: 1px solid var(--border); border-radius: 12px;">
                            <div class="stat-label mb-2"><i class="bi bi-chat-left-dots me-1"></i> Keluhan</div>
                            <p class="mb-0" style="color: var(--text-primary);">{{ $healthRecord->keluhan }}</p>
                        </div>
                    </div>
                    @endif

                    @if($healthRecord->diagnosa)
                    <div class="col-12">
                        <div class="p-3" style="background: var(--input-bg); border: 1px solid var(--border); border-radius: 12px;">
                            <div class="stat-label mb-2"><i class="bi bi-clipboard-check me-1"></i> Diagnosa</div>
                            <p class="mb-0" style="color: var(--text-primary);">{{ $healthRecord->diagnosa }}</p>
                        </div>
                    </div>
                    @endif

                    @if($healthRecord->tindakan)
                    <div class="col-12">
                        <div class="p-3" style="background: var(--input-bg); border: 1px solid var(--border); border-radius: 12px;">
                            <div class="stat-label mb-2"><i class="bi bi-bandaid me-1"></i> Tindakan</div>
                            <p class="mb-0" style="color: var(--text-primary);">{{ $healthRecord->tindakan }}</p>
                        </div>
                    </div>
                    @endif

                    @if($healthRecord->obat_diberikan)
                    <div class="col-12">
                        <div class="p-3" style="background: var(--input-bg); border: 1px solid var(--border); border-radius: 12px;">
                            <div class="stat-label mb-2"><i class="bi bi-capsule me-1"></i> Obat yang Diberikan</div>
                            <p class="mb-0" style="color: var(--text-primary); white-space: pre-wrap;">{{ $healthRecord->obat_diberikan }}</p>
                        </div>
                    </div>
                    @endif

                    @if($healthRecord->catatan)
                    <div class="col-12">
                        <div class="p-3" style="background: var(--input-bg); border: 1px solid var(--border); border-radius: 12px;">
                            <div class="stat-label mb-2"><i class="bi bi-sticky me-1"></i> Catatan Tambahan</div>
                            <p class="mb-0" style="color: var(--text-primary);">{{ $healthRecord->catatan }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if($healthRecord->pemeriksa)
            <div class="col-12 mt-3">
                <div class="p-3" style="background: rgba(59,130,246,0.05); border: 1px solid rgba(59,130,246,0.1); border-radius: 12px;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-badge" style="color: #3b82f6;"></i>
                        <span style="color: var(--text-muted); font-size: 0.85rem;">
                            Diperiksa oleh: <strong style="color: var(--text-primary);">{{ $healthRecord->pemeriksa->name }}</strong>
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-12 mt-3">
                <form action="{{ route('health-records.destroy', $healthRecord) }}" method="POST" 
                      onsubmit="return confirm('Yakin ingin menghapus rekam kesehatan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm" style="background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; padding: 0.5rem 1rem; font-weight: 600;">
                        <i class="bi bi-trash me-1"></i> Hapus Rekam Kesehatan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
