@extends('layouts.app')

@section('page-title', 'Data Kegiatan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 section-header">
    <div>
        <h4>Data Kegiatan</h4>
        <p class="mb-0">Kelola kegiatan posyandu lansia</p>
    </div>
    <a href="{{ route('kegiatan.create') }}" class="btn-accent ripple-btn">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kegiatan
    </a>
</div>

<div class="glass-card stagger-1">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('kegiatan.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari kegiatan atau lokasi..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="kampung" class="form-select" id="keg_filter_kampung">
                    <option value="">Semua Kampung</option>
                    @foreach($kampungList as $k)
                        <option value="{{ $k }}" {{ request('kampung') == $k ? 'selected' : '' }}>
                            {{ $k }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="rw" class="form-select" id="keg_filter_rw" {{ request('kampung') ? '' : 'disabled' }}>
                    <option value="">Semua RW</option>
                    @if(request('kampung') && isset($groupedRw[request('kampung')]))
                        @foreach($groupedRw[request('kampung')] as $rw)
                            <option value="{{ $rw }}" {{ request('rw') == $rw ? 'selected' : '' }}>
                                RW {{ $rw }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-accent ripple-btn">Cari</button>
                @if(request('search') || request('rw') || request('kampung'))
                    <a href="{{ route('kegiatan.index') }}" class="btn-glass">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Sasaran RW</th>
                        <th>Peserta</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatans as $i => $kegiatan)
                        <tr style="animation: cardSlideUp 0.4s cubic-bezier(.4,0,.2,1) {{ ($i * 0.04) }}s both;">
                            <td>{{ $kegiatans->firstItem() + $i }}</td>
                            <td class="fw-semibold">{{ $kegiatan->nama_kegiatan }}</td>
                            <td>{{ $kegiatan->tanggal_kegiatan->translatedFormat('d M Y') }}</td>
                            <td style="color:var(--text-secondary);">{{ $kegiatan->lokasi }}</td>
                            <td>
                                <span class="badge-glass badge-primary-glow" style="white-space:normal;max-width:150px;display:inline-block;">
                                    {{ $kegiatan->rw_label }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-glass badge-success-glow">{{ $kegiatan->kehadirans()->where('status', 'Hadir')->count() }} hadir</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('kegiatan.show', $kegiatan) }}" class="btn-action view" title="Detail"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('kegiatan.edit', $kegiatan) }}" class="btn-action edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('kegiatan.destroy', $kegiatan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Hapus"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-calendar-event"></i>
                                    <p>Belum ada data kegiatan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $kegiatans->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kampungSelect = document.getElementById('keg_filter_kampung');
    const rwSelect = document.getElementById('keg_filter_rw');
    const groupedRw = @json($groupedRw);

    kampungSelect.addEventListener('change', function() {
        const kampung = this.value;
        rwSelect.innerHTML = '<option value="">Semua RW</option>';

        if (kampung) {
            rwSelect.disabled = false;
            const rwList = groupedRw[kampung] || [];
            rwList.forEach(rw => {
                const opt = document.createElement('option');
                opt.value = rw;
                opt.textContent = 'RW ' + rw;
                rwSelect.appendChild(opt);
            });
        } else {
            rwSelect.disabled = true;
        }
    });
});
</script>
@endpush
