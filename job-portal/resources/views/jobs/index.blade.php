@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="display-4 fw-bold text-primary mb-3">Daftar Lowongan Kerja</h1>
        <p class="lead text-muted">Temukan pekerjaan impian Anda di sini</p>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Admin Actions -->
    @if(auth()->user() && auth()->user()->role === 'admin')
        <div class="d-flex gap-3 justify-content-center mb-8">
            <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-lg shadow-lg">
                <i class="fas fa-plus me-2"></i>Tambah Lowongan
            </a>
            <form action="{{ route('jobs.import') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                @csrf
                <input type="file" name="file" accept=".xlsx,.csv" class="form-control form-control-lg shadow-sm" required>
                <button type="submit" class="btn btn-outline-secondary btn-lg shadow-sm">
                    <i class="fas fa-upload me-2"></i>Import
                </button>
            </form>
        </div>
    @endif

    <!-- Jobs Grid -->
    <div class="row g-4">
        @forelse($jobs as $job)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100 shadow-lg border-0 overflow-hidden job-card-hover">
                    <!-- Logo -->
                    <div class="card-img-top position-relative overflow-hidden" style="height: 220px;">
                        @if($job->logo)
                            <img src="{{ asset('storage/' . $job->logo) }}" class="w-100 h-100 object-fit-cover" alt="{{ $job->company }} logo">
                        @else
                            <div class="w-100 h-100 bg-gradient d-flex align-items-center justify-content-center">
                                <i class="fas fa-building fa-4x text-white opacity-75"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4 d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark mb-2 lh-sm">{{ $job->title }}</h5>
                        <div class="text-small mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-building me-2 text-primary"></i>
                                <span class="fw-semibold">{{ $job->company }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-map-marker-alt me-2 text-success"></i>
                                <span>{{ $job->location }}</span>
                            </div>
                            @if($job->salary)
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas fa-dollar-sign me-2 text-warning"></i>
                                    <span>Rp {{ number_format($job->salary, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if($job->job_type)
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock me-2 text-info"></i>
                                    <span class="badge bg-gradient-{{ $job->job_type === 'Full-time' ? 'success' : 'info' }} px-2 py-1 rounded-pill fs-6">
                                        {{ $job->job_type }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="mt-auto pt-3">
                            @if(auth()->user() && auth()->user()->role === 'admin')
                                <div class="d-grid gap-2">
                                    <a href="{{ route('jobs.edit', $job) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <a href="{{ route('applications.index', $job->id) }}" class="btn btn-info">
                                        <i class="fas fa-users me-1"></i> Lihat Pelamar
                                    </a>
                                    <form action="{{ route('jobs.destroy', $job) }}" method="POST" onsubmit="return confirm('Yakin hapus?')" style="display: contents;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('apply.store', $job->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="file" name="cv" accept=".pdf" class="form-control shadow-sm @error('cv') is-invalid @enderror" required>
                                        @error('cv')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 shadow-lg">
                                        <i class="fas fa-paper-plane me-2"></i> Lamar Sekarang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-8">
                <i class="fas fa-briefcase fa-5x text-muted mb-4 opacity-50"></i>
                <h3 class="text-muted mb-3">Belum ada lowongan tersedia</h3>
                @if(auth()->user() && auth()->user()->role === 'admin')
                    <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i> Tambah Lowongan Pertama
                    </a>
                @endif
            </div>
        @endforelse
    </div>
</div>

<style>
.job-card-hover {
    transition: all 0.3s ease;
}
.job-card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
.bg-gradient-info {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}
</style>
@endsection