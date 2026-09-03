@extends('layouts.app')

@section('page-title', 'Laporan Keaktifan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 section-header">
    <div>
        <h4>Laporan Keaktifan Lansia</h4>
        <p class="mb-0">Lihat dan export laporan keaktifan</p>
    </div>
</div>

<div class="glass-card stagger-1 mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $num => $nama)
                        <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="rw" class="form-select" onchange="this.form.submit()">

                    <option value="">Semua RW</option>

                    @foreach($daftarRw as $rwItem)
                        <option value="{{ $rwItem }}"
                            {{ request('rw') == $rwItem ? 'selected' : '' }}>
                            RW {{ $rwItem }}
                        </option>
                    @endforeach

                </select>
            </div>
            <div class="col-md-6 d-flex gap-2 flex-wrap">
                <button type="submit" class="btn-accent ripple-btn"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('laporan.export-pdf', ['bulan' => $bulan, 'tahun' => $tahun, 'rw' => $rw]) }}" class="btn-glass" style="color:#ef4444;border-color:rgba(239,68,68,0.2);">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </a>
                <a href="{{ route('laporan.export-excel', ['bulan' => $bulan, 'tahun' => $tahun, 'rw' => $rw]) }}" class="btn-glass" style="color:var(--accent);border-color:rgba(16,185,129,0.2);">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Excel
                </a>
            </div>
        </form>
    </div>
</div>

<div class="glass-card stagger-2">
    <div class="card-header glass-header d-flex justify-content-between align-items-center">
        <h6>
            <i class="bi bi-table me-2" style="color:var(--accent2);"></i>
            Keaktifan Lansia &mdash; {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan] }} {{ $tahun }}
        </h6>
        <span class="badge-glass badge-primary-glow">{{ $totalKegiatanBulan }} kegiatan</span>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>RW</th>
                        <th class="text-center">Kegiatan</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-center">Persentase</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lansias as $i => $lansia)
                        <tr style="animation: cardSlideUp 0.4s cubic-bezier(.4,0,.2,1) {{ ($i * 0.04) }}s both;">
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $lansia->nama }}</td>
                            <td>{{ $lansia->jenis_kelamin }}</td>
                            <td>RW {{ $lansia->rw }}</td>
                            <td class="text-center">{{ $lansia->total_kegiatan_valid_bulan }}</td>
                            <td class="text-center fw-bold">{{ $lansia->total_hadir_bulan }}</td>
                            <td class="text-center fw-bold" style="color:var(--accent);">{{ $lansia->persentase_bulan }}%</td>
                            <td>
                                <span class="badge-glass badge-{{ $lansia->badge_bulan === 'success' ? 'success' : ($lansia->badge_bulan === 'primary' ? 'primary' : ($lansia->badge_bulan === 'warning' ? 'warning' : 'danger')) }}-glow">
                                    {{ $lansia->kategori_bulan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-file-earmark-bar-graph"></i>
                                    <p>Belum ada data lansia</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
