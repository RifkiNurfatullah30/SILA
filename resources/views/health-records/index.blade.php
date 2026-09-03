@extends('layouts.app')

@section('title', 'Rekam Kesehatan')

@section('page-title', 'Rekam Kesehatan')

@section('content')
<div class="glass-card mb-3 stagger-1">
    <div class="p-3">
        <form method="GET" action="{{ route('health-records.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Cari Lansia</label>
                    <input type="text" name="search" class="form-control" 
                           value="{{ request('search') }}" placeholder="Nama lansia...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kampung</label>
                    <select name="kampung" class="form-select" id="hr_filter_kampung">
                        <option value="">Semua Kampung</option>
                        @foreach($kampungList as $k)
                            <option value="{{ $k }}" {{ request('kampung') == $k ? 'selected' : '' }}>
                                {{ $k }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Filter RW</label>
                    <select name="rw" class="form-select" id="hr_filter_rw">
                        <option value="">Semua RW</option>
                        @foreach($groupedRw as $kampung => $rwList)
                            <optgroup label="Kampung {{ $kampung }}">
                                @foreach($rwList as $rw)
                                    <option value="{{ $rw }}" {{ request('rw') == $rw ? 'selected' : '' }}>
                                        RW {{ $rw }}
                                    </option>
                                @endforeach
                            </optgroup>
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
        <div class="d-flex gap-2">
            <button type="button" class="btn-glass" style="color:#ef4444;border-color:rgba(239,68,68,0.2);" data-bs-toggle="modal" data-bs-target="#exportHealthPdfModal">
                <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
            </button>
            <a href="{{ route('health-records.create') }}" class="btn-accent">
                <i class="bi bi-plus-circle me-1"></i> Tambah Rekam
            </a>
        </div>
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
                        <small style="color: var(--text-muted);">{{ $record->lansia->kampung ?? '' }} - RW {{ $record->lansia->rw }}</small>
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

@push('modals')
<div class="modal fade" id="exportHealthPdfModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                <h5 class="modal-title"><i class="bi bi-file-earmark-pdf me-2" style="color:#ef4444;"></i>Cetak Rekam Kesehatan PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('health-records.export-pdf') }}" target="_blank" id="formCetakPdf">
                <input type="hidden" name="lansia_id" id="exp_lansia_id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cari Berdasarkan Nama Lansia</label>
                        <div class="position-relative">
                            <input type="text" id="exp_search_name" class="form-control" placeholder="Ketik minimal 2 huruf nama lansia..." autocomplete="off">
                            <div id="exp_search_results" class="border rounded mt-1 position-absolute w-100" style="display:none; max-height:200px; overflow-y:auto; background:var(--card-bg); border-color:var(--border) !important; z-index:1060;"></div>
                        </div>
                        <div id="exp_selected_lansia" class="mt-2 p-2 rounded" style="display:none; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong id="exp_lansia_nama"></strong>
                                    <span class="ms-2" style="color:var(--text-muted); font-size:0.85rem;">
                                        Kampung <span id="exp_lansia_kampung"></span> - RW <span id="exp_lansia_rw"></span>
                                    </span>
                                </div>
                                <button type="button" class="btn-close btn-sm" id="exp_clear_lansia"></button>
                            </div>
                        </div>
                        <small style="color: var(--text-muted);"><i class="bi bi-info-circle me-1"></i>Jika memilih nama, filter kampung & RW di bawah akan diabaikan.</small>
                    </div>

                    <div id="filter_kampung_rw_container">
                        <p class="mb-2 mt-3" style="color: var(--text-muted); font-size: 0.85rem;">Atau cetak berdasarkan wilayah (kosongkan semua untuk cetak keseluruhan kelurahan):</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kampung</label>
                                <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto; border-color: var(--border) !important;">
                                    @foreach(\App\Helpers\KampungHelper::getKampungList() as $k)
                                        <div class="form-check">
                                            <input class="form-check-input exp-kmp-checkbox" type="checkbox" name="kampungs[]" value="{{ $k }}" id="exp_kmp_{{ $k }}">
                                            <label class="form-check-label" for="exp_kmp_{{ $k }}">Kampung {{ $k }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">RW</label>
                                <div class="border rounded p-2" id="exp_rw_container" style="max-height: 200px; overflow-y: auto; border-color: var(--border) !important;">
                                    <div class="text-center p-3 text-muted" id="exp_rw_empty_msg" style="font-size: 0.85rem;">
                                        Pilih tepat 1 kampung untuk memilih spesifik RW.
                                    </div>
                                    @foreach(\App\Helpers\KampungHelper::getGroupedRw() as $kampung => $rwList)
                                        <div class="exp-rw-group" data-kampung="{{ $kampung }}" style="display: none;">
                                            <div class="fw-semibold small mt-1 mb-1" style="color: var(--text-muted);">{{ $kampung }}</div>
                                            @foreach($rwList as $rw)
                                                <div class="form-check ms-2">
                                                    <input class="form-check-input exp-rw-checkbox" type="checkbox" name="rws[]" value="{{ $rw }}" id="exp_rw_{{ $rw }}">
                                                    <label class="form-check-label" for="exp_rw_{{ $rw }}">RW {{ $rw }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="dari_tanggal" class="form-control" value="{{ request('dari_tanggal') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="sampai_tanggal" class="form-control" value="{{ request('sampai_tanggal') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border);">
                    <button type="button" class="btn-glass" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-accent"><i class="bi bi-printer me-1"></i> Cetak PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kampungSelect = document.getElementById('hr_filter_kampung');
    const rwSelect = document.getElementById('hr_filter_rw');
    const groupedRw = @json($groupedRw);

    kampungSelect.addEventListener('change', function() {
        const kampung = this.value;
        rwSelect.innerHTML = '<option value="">Semua RW</option>';
        
        if (kampung === '') {
            rwSelect.disabled = true;
        } else {
            rwSelect.disabled = false;
            const rwList = groupedRw[kampung] || [];
            rwList.forEach(rw => {
                const opt = document.createElement('option');
                opt.value = rw;
                opt.textContent = 'RW ' + rw;
                rwSelect.appendChild(opt);
            });
        }
    });

    if (kampungSelect.value === '') {
        rwSelect.disabled = true;
    }

    const expSearchName = document.getElementById('exp_search_name');
    const expSearchResults = document.getElementById('exp_search_results');
    const expLansiaId = document.getElementById('exp_lansia_id');
    const expSelectedLansia = document.getElementById('exp_selected_lansia');
    const expLansiaNama = document.getElementById('exp_lansia_nama');
    const expLansiaKampung = document.getElementById('exp_lansia_kampung');
    const expLansiaRw = document.getElementById('exp_lansia_rw');
    const expClearLansia = document.getElementById('exp_clear_lansia');
    const expKampungContainer = document.getElementById('filter_kampung_rw_container');
    const expKmpCheckboxes = document.querySelectorAll('.exp-kmp-checkbox');
    const expRwGroups = document.querySelectorAll('.exp-rw-group');
    const expRwEmptyMsg = document.getElementById('exp_rw_empty_msg');

    let searchTimeout;

    expSearchName.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        if (query.length < 2) {
            expSearchResults.style.display = 'none';
            return;
        }
        searchTimeout = setTimeout(function() {
            fetch('{{ route("api.lansia-search") }}?q=' + encodeURIComponent(query))
                .then(r => r.json())
                .then(data => {
                    if (data.length === 0) {
                        expSearchResults.innerHTML = '<div class="p-2 text-center" style="color:var(--text-muted);font-size:0.85rem;">Tidak ditemukan</div>';
                    } else {
                        expSearchResults.innerHTML = data.map(l =>
                            '<div class="p-2 exp-search-item" style="cursor:pointer;border-bottom:1px solid var(--border);" data-id="' + l.id + '" data-nama="' + l.nama + '" data-kampung="' + l.kampung + '" data-rw="' + l.rw + '">' +
                            '<strong>' + l.nama + '</strong>' +
                            '<small class="ms-2" style="color:var(--text-muted);">Kampung ' + l.kampung + ' - RW ' + l.rw + '</small>' +
                            '</div>'
                        ).join('');
                    }
                    expSearchResults.style.display = 'block';

                    expSearchResults.querySelectorAll('.exp-search-item').forEach(item => {
                        item.addEventListener('click', function() {
                            selectLansia(this.dataset.id, this.dataset.nama, this.dataset.kampung, this.dataset.rw);
                        });
                        item.addEventListener('mouseenter', function() { this.style.background = 'var(--table-hover)'; });
                        item.addEventListener('mouseleave', function() { this.style.background = ''; });
                    });
                });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!expSearchResults.contains(e.target) && e.target !== expSearchName) {
            expSearchResults.style.display = 'none';
        }
    });

    function selectLansia(id, nama, kampung, rw) {
        expLansiaId.value = id;
        expLansiaNama.textContent = nama;
        expLansiaKampung.textContent = kampung;
        expLansiaRw.textContent = rw;
        expSelectedLansia.style.display = 'block';
        expSearchName.value = '';
        expSearchResults.style.display = 'none';
        updateExportLogic();
    }

    expClearLansia.addEventListener('click', function() {
        expLansiaId.value = '';
        expSelectedLansia.style.display = 'none';
        updateExportLogic();
    });

    function updateExportLogic() {
        if (expLansiaId.value !== '') {
            expKampungContainer.style.opacity = '0.4';
            expKampungContainer.style.pointerEvents = 'none';
        } else {
            expKampungContainer.style.opacity = '1';
            expKampungContainer.style.pointerEvents = 'auto';
        }

        let selectedKampungs = Array.from(expKmpCheckboxes).filter(cb => cb.checked).map(cb => cb.value);

        if (selectedKampungs.length === 1) {
            expRwEmptyMsg.style.display = 'none';
            expRwGroups.forEach(group => {
                if (group.dataset.kampung === selectedKampungs[0]) {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                    group.querySelectorAll('.exp-rw-checkbox').forEach(cb => cb.checked = false);
                }
            });
        } else {
            expRwEmptyMsg.style.display = selectedKampungs.length > 1 ? 'none' : 'block';
            if (selectedKampungs.length > 1) {
                expRwEmptyMsg.style.display = 'block';
                expRwEmptyMsg.textContent = 'Pilihan RW tidak tersedia jika memilih lebih dari 1 kampung.';
            } else {
                expRwEmptyMsg.textContent = 'Pilih tepat 1 kampung untuk memilih spesifik RW.';
            }
            expRwGroups.forEach(group => {
                group.style.display = 'none';
                group.querySelectorAll('.exp-rw-checkbox').forEach(cb => cb.checked = false);
            });
        }
    }

    expKmpCheckboxes.forEach(cb => cb.addEventListener('change', updateExportLogic));
    updateExportLogic();
});
</script>
@endpush
