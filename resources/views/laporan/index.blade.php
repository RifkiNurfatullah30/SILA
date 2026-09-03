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
            <div class="col-md-2">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $num => $nama)
                        <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Kampung</label>
                <select name="kampung" class="form-select" id="lap_filter_kampung">
                    <option value="">Semua Kampung</option>
                    @foreach($kampungList as $k)
                        <option value="{{ $k }}" {{ request('kampung') == $k ? 'selected' : '' }}>
                            {{ $k }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">RW</label>
                <select name="rw" class="form-select" id="lap_filter_rw" {{ request('kampung') ? '' : 'disabled' }}>
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
            <div class="col-md-4 d-flex gap-2 flex-wrap">
                <button type="submit" class="btn-accent ripple-btn"><i class="bi bi-funnel me-1"></i> Filter</button>
                <button type="button" class="btn-glass" style="color:#ef4444;border-color:rgba(239,68,68,0.2);" data-bs-toggle="modal" data-bs-target="#exportLaporanModal">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </button>
                <button type="button" class="btn-glass" style="color:var(--accent);border-color:rgba(16,185,129,0.2);" data-bs-toggle="modal" data-bs-target="#exportExcelModal">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Excel
                </button>
            </div>
        </form>
    </div>
</div>

<div class="glass-card stagger-2">
    <div class="card-header glass-header d-flex justify-content-between align-items-center">
        <h6>
            <i class="bi bi-table me-2" style="color:var(--accent2);"></i>
            Keaktifan Lansia &mdash; {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan] }} {{ $tahun }}
            @if(request('kampung'))
                <span class="badge-glass badge-primary-glow ms-2">Kampung {{ request('kampung') }}</span>
            @endif
            @if(request('rw'))
                <span class="badge-glass badge-primary-glow ms-2">RW {{ request('rw') }}</span>
            @endif
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
                        <th>Kampung</th>
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
                            <td>{{ $lansia->kampung ?? '-' }}</td>
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

@push('modals')
<div class="modal fade" id="exportLaporanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                <h5 class="modal-title"><i class="bi bi-file-earmark-pdf me-2" style="color:#ef4444;"></i>Export Laporan Keaktifan PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('laporan.export-pdf') }}" target="_blank">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <div class="modal-body">
                    <p class="mb-3" style="color: var(--text-muted); font-size: 0.85rem;">Pilih lingkup export. Bisa memilih beberapa kampung dan/atau RW sekaligus.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kampung</label>
                            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto; border-color: var(--border) !important;">
                                @foreach(\App\Helpers\KampungHelper::getKampungList() as $k)
                                    <div class="form-check">
                                        <input class="form-check-input pdf-kmp-checkbox" type="checkbox" name="kampungs[]" value="{{ $k }}" id="pdf_kmp_{{ $k }}">
                                        <label class="form-check-label" for="pdf_kmp_{{ $k }}">Kampung {{ $k }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">RW</label>
                            <div class="border rounded p-2 pdf-rw-container" style="max-height: 200px; overflow-y: auto; border-color: var(--border) !important;">
                                <div class="text-center p-3 text-muted pdf-rw-empty-msg" style="font-size: 0.85rem;">
                                    Pilih tepat 1 kampung untuk memilih spesifik RW.
                                </div>
                                @foreach(\App\Helpers\KampungHelper::getGroupedRw() as $kmpg => $rwList)
                                    <div class="pdf-rw-group" data-kampung="{{ $kmpg }}" style="display: none;">
                                        <div class="fw-semibold small mt-1 mb-1" style="color: var(--text-muted);">{{ $kmpg }}</div>
                                        @foreach($rwList as $rwItem)
                                            <div class="form-check ms-2">
                                                <input class="form-check-input pdf-rw-checkbox" type="checkbox" name="rws[]" value="{{ $rwItem }}" id="pdf_rw_{{ $rwItem }}">
                                                <label class="form-check-label" for="pdf_rw_{{ $rwItem }}">RW {{ $rwItem }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 p-2 rounded" style="background: rgba(16,185,129,0.05); border: 1px solid rgba(16,185,129,0.15);">
                        <small style="color: var(--text-muted);"><i class="bi bi-info-circle me-1"></i>Kosongkan semua pilihan untuk export keseluruhan data.</small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border);">
                    <button type="button" class="btn-glass" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-accent"><i class="bi bi-file-earmark-pdf me-1"></i> Export PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="exportExcelModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                <h5 class="modal-title"><i class="bi bi-file-earmark-spreadsheet me-2" style="color:var(--accent);"></i>Export Laporan Keaktifan Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('laporan.export-excel') }}" target="_blank">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <div class="modal-body">
                    <p class="mb-3" style="color: var(--text-muted); font-size: 0.85rem;">Pilih lingkup export. Bisa memilih beberapa kampung dan/atau RW sekaligus.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kampung</label>
                            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto; border-color: var(--border) !important;">
                                @foreach(\App\Helpers\KampungHelper::getKampungList() as $k)
                                    <div class="form-check">
                                        <input class="form-check-input xls-kmp-checkbox" type="checkbox" name="kampungs[]" value="{{ $k }}" id="xls_kmp_{{ $k }}">
                                        <label class="form-check-label" for="xls_kmp_{{ $k }}">Kampung {{ $k }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">RW</label>
                            <div class="border rounded p-2 xls-rw-container" style="max-height: 200px; overflow-y: auto; border-color: var(--border) !important;">
                                <div class="text-center p-3 text-muted xls-rw-empty-msg" style="font-size: 0.85rem;">
                                    Pilih tepat 1 kampung untuk memilih spesifik RW.
                                </div>
                                @foreach(\App\Helpers\KampungHelper::getGroupedRw() as $kmpg => $rwList)
                                    <div class="xls-rw-group" data-kampung="{{ $kmpg }}" style="display: none;">
                                        <div class="fw-semibold small mt-1 mb-1" style="color: var(--text-muted);">{{ $kmpg }}</div>
                                        @foreach($rwList as $rwItem)
                                            <div class="form-check ms-2">
                                                <input class="form-check-input xls-rw-checkbox" type="checkbox" name="rws[]" value="{{ $rwItem }}" id="xls_rw_{{ $rwItem }}">
                                                <label class="form-check-label" for="xls_rw_{{ $rwItem }}">RW {{ $rwItem }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 p-2 rounded" style="background: rgba(16,185,129,0.05); border: 1px solid rgba(16,185,129,0.15);">
                        <small style="color: var(--text-muted);"><i class="bi bi-info-circle me-1"></i>Kosongkan semua pilihan untuk export keseluruhan data.</small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border);">
                    <button type="button" class="btn-glass" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-accent"><i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Excel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kampungSelect = document.getElementById('lap_filter_kampung');
    const rwSelect = document.getElementById('lap_filter_rw');
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

    if (kampungSelect.value === '') {
        rwSelect.disabled = true;
    }

    function setupModalLogic(prefix) {
        const kmpCheckboxes = document.querySelectorAll('.' + prefix + '-kmp-checkbox');
        const rwGroups = document.querySelectorAll('.' + prefix + '-rw-group');
        const rwEmptyMsg = document.querySelector('.' + prefix + '-rw-empty-msg');

        function updateLogic() {
            let selected = Array.from(kmpCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
            if (selected.length === 1) {
                rwEmptyMsg.style.display = 'none';
                rwGroups.forEach(group => {
                    if (group.dataset.kampung === selected[0]) {
                        group.style.display = 'block';
                    } else {
                        group.style.display = 'none';
                        group.querySelectorAll('.' + prefix + '-rw-checkbox').forEach(cb => cb.checked = false);
                    }
                });
            } else {
                rwEmptyMsg.style.display = 'block';
                if (selected.length > 1) {
                    rwEmptyMsg.textContent = 'Pilihan RW tidak tersedia jika memilih lebih dari 1 kampung.';
                } else {
                    rwEmptyMsg.textContent = 'Pilih tepat 1 kampung untuk memilih spesifik RW.';
                }
                rwGroups.forEach(group => {
                    group.style.display = 'none';
                    group.querySelectorAll('.' + prefix + '-rw-checkbox').forEach(cb => cb.checked = false);
                });
            }
        }

        kmpCheckboxes.forEach(cb => cb.addEventListener('change', updateLogic));
        updateLogic();
    }

    setupModalLogic('pdf');
    setupModalLogic('xls');
});
</script>
@endpush
