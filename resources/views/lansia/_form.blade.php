<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $lansia->nama ?? '') }}" required>
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="nik" class="form-label">NIK (Opsional)</label>
        <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik', $lansia->nik ?? '') }}" placeholder="16 Digit NIK" maxlength="16">
        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label for="rw" class="form-label">RW <span class="text-danger">*</span></label>
        <select class="form-select @error('rw') is-invalid @enderror" id="rw" name="rw" required>
            <option value="">-- Pilih RW --</option>
            @foreach(\App\Helpers\KampungHelper::getGroupedRw() as $kampung => $rwList)
                <optgroup label="Kampung {{ $kampung }}">
                    @foreach($rwList as $rwItem)
                        <option value="{{ $rwItem }}" {{ old('rw', $lansia->rw ?? '') == $rwItem ? 'selected' : '' }}>
                            RW {{ $rwItem }}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        @error('rw') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Kampung</label>
        <input type="text" class="form-control" id="kampung_display" readonly
               value="{{ old('rw', $lansia->rw ?? '') ? \App\Helpers\KampungHelper::getKampungByRw(old('rw', $lansia->rw ?? '')) : '' }}"
               style="background: var(--input-bg, #f8f9fa); color: var(--text-muted, #6c757d);">
    </div>
    <div class="col-md-6">
        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
            <option value="">-- Pilih --</option>
            <option value="Laki-laki" {{ old('jenis_kelamin', $lansia->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ old('jenis_kelamin', $lansia->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $lansia->alamat ?? '') }}</textarea>
        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rwSelect = document.getElementById('rw');
    const kampungDisplay = document.getElementById('kampung_display');
    const kampungMap = @json(\App\Helpers\KampungHelper::KAMPUNG_RW_MAP);

    function updateKampung() {
        const rw = rwSelect.value;
        let found = '';
        for (const [kampung, rwList] of Object.entries(kampungMap)) {
            if (rwList.includes(rw)) {
                found = kampung;
                break;
            }
        }
        kampungDisplay.value = found ? found : '';
    }

    rwSelect.addEventListener('change', updateKampung);
});
</script>
@endpush
