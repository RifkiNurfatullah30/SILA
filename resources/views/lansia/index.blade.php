@extends('layouts.app')

@section('page-title', 'Data Lansia')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 section-header">
    <div>
        <h4>Data Lansia</h4>
        <p class="mb-0">Kelola data lansia yang terdaftar</p>
    </div>
    <a href="{{ route('lansia.create') }}" class="btn-accent ripple-btn">
        <i class="bi bi-plus-lg me-1"></i> Tambah Lansia
    </a>
</div>

<div class="glass-card stagger-1">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('lansia.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau alamat..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="kampung" class="form-select" id="filter_kampung">
                    <option value="">Semua Kampung</option>
                    @foreach($kampungList as $k)
                        <option value="{{ $k }}" {{ request('kampung') == $k ? 'selected' : '' }}>
                            Kampung {{ $k }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="rw" class="form-select" id="filter_rw" {{ request('kampung') ? '' : 'disabled' }}>
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
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-accent ripple-btn">Cari</button>
                @if(request('search') || request('rw') || request('kampung'))
                    <a href="{{ route('lansia.index') }}" class="btn-glass">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kampung</th>
                        <th>RW</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>Keaktifan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lansias as $i => $lansia)
                        <tr style="animation: cardSlideUp 0.4s cubic-bezier(.4,0,.2,1) {{ ($i * 0.04) }}s both;">
                            <td>{{ $lansias->firstItem() + $i }}</td>
                            <td class="fw-semibold">{{ $lansia->nama }}</td>
                            <td>{{ $lansia->kampung ?? '-' }}</td>
                            <td>{{ $lansia->rw }}</td>
                            <td>{{ $lansia->jenis_kelamin }}</td>
                            <td>{{ Str::limit($lansia->alamat, 30) }}</td>
                            <td>
                                <span class="badge-glass badge-{{ $lansia->badge_keaktifan === 'success' ? 'success' : ($lansia->badge_keaktifan === 'primary' ? 'primary' : ($lansia->badge_keaktifan === 'warning' ? 'warning' : 'danger')) }}-glow">
                                    {{ $lansia->persentase_keaktifan }}% &middot; {{ $lansia->kategori_keaktifan }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('lansia.show', $lansia) }}" class="btn-action view" title="Detail"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('lansia.edit', $lansia) }}" class="btn-action edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('lansia.destroy', $lansia) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Hapus"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    <p>Belum ada data lansia</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $lansias->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kampungSelect = document.getElementById('filter_kampung');
    const rwSelect = document.getElementById('filter_rw');
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
