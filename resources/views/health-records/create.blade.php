@extends('layouts.app')

@section('title', 'Tambah Rekam Kesehatan')

@section('page-title', 'Tambah Rekam Kesehatan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="glass-card stagger-1">
            <div class="glass-header d-flex justify-content-between align-items-center">
                <h6><i class="bi bi-heart-pulse me-2"></i> Form Rekam Kesehatan Baru</h6>
                <a href="{{ route('health-records.index') }}" class="btn-glass btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="p-4">
                <form action="{{ route('health-records.store') }}" method="POST">
                    @csrf
                    @include('health-records._form')
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn-accent">
                            <i class="bi bi-check-circle me-1"></i> Simpan
                        </button>
                        <a href="{{ route('health-records.index') }}" class="btn-glass">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
