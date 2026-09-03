<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan ?? '') }}" required>
        @error('nama_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="tanggal_kegiatan" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('tanggal_kegiatan') is-invalid @enderror" id="tanggal_kegiatan" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', isset($kegiatan) ? $kegiatan->tanggal_kegiatan->format('Y-m-d') : '') }}" required>
        @error('tanggal_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi', $kegiatan->lokasi ?? '') }}" required>
        @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Sasaran Kampung</label>
        <div class="border rounded p-2 mb-2" style="border-color: var(--border) !important;">
            @foreach($groupedRw as $kampung => $rwList)
                <div class="form-check">
                    <input class="form-check-input keg-kmp-checkbox" type="checkbox" value="{{ $kampung }}" id="keg_kmp_{{ $kampung }}">
                    <label class="form-check-label" for="keg_kmp_{{ $kampung }}">Kampung {{ $kampung }}</label>
                </div>
            @endforeach
        </div>
        <div id="keg_rw_section" style="display: none;">
            <label class="form-label">Pilih RW (Bisa pilih beberapa)</label>
            @php
                $selectedRws = old('rws', isset($kegiatan) ? $kegiatan->rw_array : []);
            @endphp
            <div class="border rounded p-2" style="max-height: 180px; overflow-y: auto; border-color: var(--border) !important;">
                @foreach($groupedRw as $kampung => $rwList)
                    <div class="rw-group" data-kampung="{{ $kampung }}" style="display: none;">
                        <div class="fw-semibold small mt-1 mb-1" style="color: var(--text-muted);">Kampung {{ $kampung }}</div>
                        <div class="d-flex flex-wrap gap-2 ms-2 mb-2">
                            @foreach($rwList as $rw)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input keg-rw-checkbox" type="checkbox" name="rws[]" id="rw_{{ $rw }}" value="{{ $rw }}" {{ in_array($rw, $selectedRws) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rw_{{ $rw }}">RW {{ $rw }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-1">
            <small style="color: var(--text-muted);"><i class="bi bi-info-circle me-1"></i>Kosongkan jika kegiatan ini ditujukan untuk semua RW.</small>
        </div>
        @error('rws') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label for="keterangan" class="form-label">Keterangan</label>
        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $kegiatan->keterangan ?? '') }}</textarea>
        @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kmpCheckboxes = document.querySelectorAll('.keg-kmp-checkbox');
    const rwSection = document.getElementById('keg_rw_section');
    const rwGroups = document.querySelectorAll('.rw-group');
    const selectedRws = @json($selectedRws ?? []);
    const groupedRw = @json($groupedRw);

    if (selectedRws.length > 0) {
        let kampungs = new Set();
        for (const [kampung, rwList] of Object.entries(groupedRw)) {
            for (const rw of selectedRws) {
                if (rwList.includes(rw)) {
                    kampungs.add(kampung);
                }
            }
        }
        kampungs.forEach(k => {
            kmpCheckboxes.forEach(cb => {
                if (cb.value === k) cb.checked = true;
            });
        });
        updateRwVisibility();
    }

    function updateRwVisibility() {
        const selected = Array.from(kmpCheckboxes).filter(cb => cb.checked).map(cb => cb.value);

        if (selected.length === 0) {
            rwSection.style.display = 'none';
            rwGroups.forEach(group => {
                group.style.display = 'none';
                group.querySelectorAll('.keg-rw-checkbox').forEach(cb => cb.checked = false);
            });
        } else {
            rwSection.style.display = 'block';
            rwGroups.forEach(group => {
                if (selected.includes(group.dataset.kampung)) {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                    group.querySelectorAll('.keg-rw-checkbox').forEach(cb => cb.checked = false);
                }
            });
        }
    }

    kmpCheckboxes.forEach(cb => cb.addEventListener('change', updateRwVisibility));
});
</script>
@endpush
