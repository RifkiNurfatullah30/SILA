@extends('layouts.app')

@section('page-title', 'Input Kehadiran')

@section('content')
<div class="mb-4 section-header">
    <h4>Input Kehadiran Lansia</h4>
    <p class="mb-0">Pilih kegiatan untuk menginput kehadiran</p>
</div>

<div class="glass-card stagger-1 mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('kehadiran.index') }}" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label for="kegiatan_id" class="form-label">Pilih Kegiatan</label>
                <select class="form-select" name="kegiatan_id" id="kegiatan_id" onchange="this.form.submit()">
                    <option value="">-- Pilih Kegiatan --</option>
                    @foreach($kegiatans as $kegiatan)
                        <option value="{{ $kegiatan->id }}" {{ request('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}>
                            {{ $kegiatan->nama_kegiatan }} - {{ $kegiatan->tanggal_kegiatan->translatedFormat('d M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
             <div class="col-md-3">
                <select name="rw" class="form-select" onchange="this.form.submit()">

                    <option value="">Semua RW</option>

                    @foreach($daftarRw as $rw)
                        <option value="{{ $rw }}"
                            {{ request('rw') == $rw ? 'selected' : '' }}>
                            RW {{ $rw }}
                        </option>
                    @endforeach

                </select>
            </div>
        </form>
    </div>
</div>

@if($selectedKegiatan)
<div class="glass-card stagger-2">
    <div class="card-header glass-header d-flex justify-content-between align-items-center">
        <h6>
            <i class="bi bi-clipboard2-check me-2" style="color:var(--accent);"></i>
            {{ $selectedKegiatan->nama_kegiatan }} &mdash; {{ $selectedKegiatan->tanggal_kegiatan->translatedFormat('d F Y') }}
        </h6>
        <span class="badge-glass badge-primary-glow">{{ $selectedKegiatan->lokasi }}</span>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('kehadiran.store') }}">
            @csrf
            <input type="hidden" name="kegiatan_id" value="{{ $selectedKegiatan->id }}">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lansia</th>
                            <th>RW</th>
                            <th>Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lansias as $i => $lansia)
                            <tr style="animation: cardSlideUp 0.4s cubic-bezier(.4,0,.2,1) {{ ($i * 0.03) }}s both;">
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $lansia->nama }}</td>
                                <td>RW {{ $lansia->rw }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <input type="radio" class="btn-check" name="kehadiran[{{ $lansia->id }}]" id="hadir_{{ $lansia->id }}" value="Hadir" {{ ($kehadiranMap[$lansia->id] ?? '') === 'Hadir' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success btn-sm" for="hadir_{{ $lansia->id }}"><i class="bi bi-check-circle me-1"></i>Hadir</label>

                                        <input type="radio" class="btn-check" name="kehadiran[{{ $lansia->id }}]" id="tidak_hadir_{{ $lansia->id }}" value="Tidak Hadir" {{ ($kehadiranMap[$lansia->id] ?? 'Tidak Hadir') === 'Tidak Hadir' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger btn-sm" for="tidak_hadir_{{ $lansia->id }}"><i class="bi bi-x-circle me-1"></i>Tidak Hadir</label>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="bi bi-people"></i>
                                        <p>Belum ada data lansia. <a href="{{ route('lansia.create') }}" style="color:var(--accent);">Tambah lansia</a> terlebih dahulu.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($lansias->isNotEmpty())
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn-accent ripple-btn" style="padding:0.7rem 2rem;font-size:0.95rem;">
                        <i class="bi bi-save me-1"></i> Simpan Kehadiran
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
@endif
@endsection
