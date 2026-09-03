<div class="row g-3">
    <div class="col-md-6">
        <label for="lansia_search" class="form-label">Lansia <span class="text-danger">*</span></label>
        <div class="position-relative">
            <input type="text"
                   id="lansia_search"
                   class="form-control @error('lansia_id') is-invalid @enderror"
                   placeholder="Ketik nama lansia..."
                   autocomplete="off"
                   value="{{ old('lansia_id', $healthRecord?->lansia_id) ? ($lansiaList->firstWhere('id', old('lansia_id', $healthRecord?->lansia_id))?->nama ?? '') : '' }}">
            <input type="hidden" name="lansia_id" id="lansia_id" value="{{ old('lansia_id', $healthRecord?->lansia_id) }}">
            <div id="lansia_dropdown" class="position-absolute w-100" style="z-index:1050; display:none; max-height:200px; overflow-y:auto; border:1px solid var(--border); border-radius:0 0 8px 8px; background:var(--card-bg); box-shadow:0 4px 12px rgba(0,0,0,0.15);"></div>
        </div>
        @error('lansia_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="tanggal_pemeriksaan" class="form-label">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_pemeriksaan" id="tanggal_pemeriksaan" 
               class="form-control @error('tanggal_pemeriksaan') is-invalid @enderror" 
               value="{{ old('tanggal_pemeriksaan', $healthRecord?->tanggal_pemeriksaan?->format('Y-m-d')) }}">
        @error('tanggal_pemeriksaan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mt-4">
        <h6 class="text-uppercase" style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; letter-spacing: 1px;">
            <i class="bi bi-activity me-2"></i>Tanda Vital
        </h6>
        <hr style="border-color: var(--border); margin-top: 0.5rem;">
    </div>

    <div class="col-md-4">
        <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
        <input type="number" step="0.01" name="berat_badan" id="berat_badan" 
               class="form-control @error('berat_badan') is-invalid @enderror" 
               value="{{ old('berat_badan', $healthRecord?->berat_badan) }}" 
               placeholder="Contoh: 65.5">
        @error('berat_badan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
        <input type="number" step="0.01" name="tinggi_badan" id="tinggi_badan" 
               class="form-control @error('tinggi_badan') is-invalid @enderror" 
               value="{{ old('tinggi_badan', $healthRecord?->tinggi_badan) }}" 
               placeholder="Contoh: 165">
        @error('tinggi_badan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">BMI</label>
        <input type="text" class="form-control" id="bmi_display" readonly placeholder="Otomatis terhitung" 
               style="background: var(--input-bg); color: var(--text-muted);">
    </div>

    <div class="col-md-6">
        <label for="tekanan_darah_sistolik" class="form-label">Tekanan Darah Sistolik (mmHg)</label>
        <input type="number" step="0.01" name="tekanan_darah_sistolik" id="tekanan_darah_sistolik" 
               class="form-control @error('tekanan_darah_sistolik') is-invalid @enderror" 
               value="{{ old('tekanan_darah_sistolik', $healthRecord?->tekanan_darah_sistolik) }}" 
               placeholder="Contoh: 120">
        @error('tekanan_darah_sistolik')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="tekanan_darah_diastolik" class="form-label">Tekanan Darah Diastolik (mmHg)</label>
        <input type="number" step="0.01" name="tekanan_darah_diastolik" id="tekanan_darah_diastolik" 
               class="form-control @error('tekanan_darah_diastolik') is-invalid @enderror" 
               value="{{ old('tekanan_darah_diastolik', $healthRecord?->tekanan_darah_diastolik) }}" 
               placeholder="Contoh: 80">
        @error('tekanan_darah_diastolik')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="gula_darah" class="form-label">Gula Darah (mg/dL)</label>
        <input type="number" step="0.01" name="gula_darah" id="gula_darah" 
               class="form-control @error('gula_darah') is-invalid @enderror" 
               value="{{ old('gula_darah', $healthRecord?->gula_darah) }}" 
               placeholder="Contoh: 100">
        @error('gula_darah')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="kolesterol" class="form-label">Kolesterol (mg/dL)</label>
        <input type="number" step="0.01" name="kolesterol" id="kolesterol" 
               class="form-control @error('kolesterol') is-invalid @enderror" 
               value="{{ old('kolesterol', $healthRecord?->kolesterol) }}" 
               placeholder="Contoh: 200">
        @error('kolesterol')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="asam_urat" class="form-label">Asam Urat (mg/dL)</label>
        <input type="number" step="0.01" name="asam_urat" id="asam_urat" 
               class="form-control @error('asam_urat') is-invalid @enderror" 
               value="{{ old('asam_urat', $healthRecord?->asam_urat) }}" 
               placeholder="Contoh: 6.5">
        @error('asam_urat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mt-4">
        <h6 class="text-uppercase" style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; letter-spacing: 1px;">
            <i class="bi bi-clipboard2-pulse me-2"></i>Pemeriksaan Klinis
        </h6>
        <hr style="border-color: var(--border); margin-top: 0.5rem;">
    </div>

    <div class="col-md-6">
        <label for="keluhan" class="form-label">Keluhan</label>
        <textarea name="keluhan" id="keluhan" rows="3" 
                  class="form-control @error('keluhan') is-invalid @enderror" 
                  placeholder="Keluhan yang dirasakan pasien">{{ old('keluhan', $healthRecord?->keluhan) }}</textarea>
        @error('keluhan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="diagnosa" class="form-label">Diagnosa</label>
        <textarea name="diagnosa" id="diagnosa" rows="3" 
                  class="form-control @error('diagnosa') is-invalid @enderror" 
                  placeholder="Diagnosa dari pemeriksaan">{{ old('diagnosa', $healthRecord?->diagnosa) }}</textarea>
        @error('diagnosa')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="tindakan" class="form-label">Tindakan</label>
        <textarea name="tindakan" id="tindakan" rows="3" 
                  class="form-control @error('tindakan') is-invalid @enderror" 
                  placeholder="Tindakan yang dilakukan">{{ old('tindakan', $healthRecord?->tindakan) }}</textarea>
        @error('tindakan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="obat_diberikan" class="form-label">Obat yang Diberikan</label>
        <textarea name="obat_diberikan" id="obat_diberikan" rows="3" 
                  class="form-control @error('obat_diberikan') is-invalid @enderror" 
                  placeholder="Daftar obat yang diberikan dan dosisnya">{{ old('obat_diberikan', $healthRecord?->obat_diberikan) }}</textarea>
        @error('obat_diberikan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="catatan" class="form-label">Catatan Tambahan</label>
        <textarea name="catatan" id="catatan" rows="3" 
                  class="form-control @error('catatan') is-invalid @enderror" 
                  placeholder="Catatan tambahan dari pemeriksa">{{ old('catatan', $healthRecord?->catatan) }}</textarea>
        @error('catatan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const beratInput = document.getElementById('berat_badan');
    const tinggiInput = document.getElementById('tinggi_badan');
    const bmiDisplay = document.getElementById('bmi_display');

    function calculateBMI() {
        const berat = parseFloat(beratInput.value);
        const tinggi = parseFloat(tinggiInput.value);
        
        if (berat && tinggi && tinggi > 0) {
            const tinggiMeter = tinggi / 100;
            const bmi = (berat / (tinggiMeter * tinggiMeter)).toFixed(2);
            
            let kategori = '';
            if (bmi < 18.5) kategori = 'Kurus';
            else if (bmi < 25) kategori = 'Normal';
            else if (bmi < 30) kategori = 'Overweight';
            else kategori = 'Obesitas';
            
            bmiDisplay.value = `${bmi} (${kategori})`;
        } else {
            bmiDisplay.value = '';
        }
    }

    beratInput.addEventListener('input', calculateBMI);
    tinggiInput.addEventListener('input', calculateBMI);
    calculateBMI();

    const lansiaData = @json($lansiaList->map(fn($l) => ['id' => $l->id, 'nama' => $l->nama, 'rw' => $l->rw]));
    const searchInput = document.getElementById('lansia_search');
    const hiddenInput = document.getElementById('lansia_id');
    const dropdown = document.getElementById('lansia_dropdown');

    function renderDropdown(items) {
        if (items.length === 0) {
            dropdown.innerHTML = '<div style="padding:8px 12px;color:var(--text-muted);font-size:0.85rem;">Tidak ditemukan</div>';
        } else {
            dropdown.innerHTML = items.map(item =>
                `<div class="lansia-option" data-id="${item.id}" data-nama="${item.nama}" style="padding:8px 12px;cursor:pointer;color:var(--text-primary);font-size:0.9rem;border-bottom:1px solid var(--border);">
                    <div class="fw-semibold">${item.nama}</div>
                    <small style="color:var(--text-muted);">RW ${item.rw}</small>
                </div>`
            ).join('');
        }
        dropdown.style.display = 'block';
    }

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        hiddenInput.value = '';
        if (query.length === 0) {
            dropdown.style.display = 'none';
            return;
        }
        const filtered = lansiaData.filter(l => l.nama.toLowerCase().includes(query));
        renderDropdown(filtered);
    });

    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length > 0) {
            const query = this.value.toLowerCase().trim();
            const filtered = lansiaData.filter(l => l.nama.toLowerCase().includes(query));
            renderDropdown(filtered);
        }
    });

    dropdown.addEventListener('click', function(e) {
        const option = e.target.closest('.lansia-option');
        if (option) {
            hiddenInput.value = option.dataset.id;
            searchInput.value = option.dataset.nama;
            dropdown.style.display = 'none';
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
});
</script>
@endpush
