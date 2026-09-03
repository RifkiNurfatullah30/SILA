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
        <label class="form-label">Sasaran RW (Kosongkan jika untuk semua RW)</label>
        <div class="d-flex flex-wrap gap-2 mt-2">
            @php
                $selectedRws = old('rws', isset($kegiatan) ? $kegiatan->rw_array : []);
            @endphp
            @foreach($daftarRw as $rw)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="rws[]" id="rw_{{ $rw }}" value="{{ $rw }}" {{ in_array($rw, $selectedRws) ? 'checked' : '' }}>
                    <label class="form-check-label" for="rw_{{ $rw }}">RW {{ $rw }}</label>
                </div>
            @endforeach
        </div>
        @error('rws') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label for="keterangan" class="form-label">Keterangan</label>
        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $kegiatan->keterangan ?? '') }}</textarea>
        @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
