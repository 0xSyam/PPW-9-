@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                @if($job->logo)
                    <img src="{{ asset('storage/' . $job->logo) }}" class="card-img-top" alt="{{ $job->company }} logo" style="height: 300px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                        <i class="fas fa-building fa-5x text-muted"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h1 class="card-title mb-4">{{ $job->title }}</h1>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-building me-2"></i>Company:</strong> {{ $job->company }}</p>
                            <p><strong><i class="fas fa-map-marker-alt me-2"></i>Location:</strong> {{ $job->location }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($job->salary)
                                <p><strong><i class="fas fa-dollar-sign me-2"></i>Salary:</strong> Rp {{ number_format($job->salary, 0, ',', '.') }}</p>
                            @endif
                            @if($job->job_type)
                                <p><strong><i class="fas fa-clock me-2"></i>Type:</strong> 
                                    <span class="badge bg-{{ $job->job_type === 'Full-time' ? 'success' : 'info' }} fs-6 px-3 py-2">{{ $job->job_type }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="job-description">
                        <h5>Job Description</h5>
                        <p class="lead">{!! nl2br(e($job->description)) !!}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @auth
                @if(auth()->user()->role === 'admin')
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-cog"></i> Admin Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('jobs.edit', $job) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Lowongan
                                </a>
                                <a href="{{ route('applications.index', $job->id) }}" class="btn btn-info">
                                    <i class="fas fa-users"></i> Lihat Pelamar
                                </a>
                                <form action="{{ route('jobs.destroy', $job) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin hapus lowongan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> Hapus Lowongan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-paper-plane"></i> Lamar Pekerjaan Ini</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('apply.store', $job->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="cv" class="form-label">Upload CV <span class="text-danger">*</span></label>
                                    <input type="file" name="cv" id="cv" accept=".pdf" class="form-control @error('cv') is-invalid @enderror" required>
                                    @error('cv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">PDF (max 2MB)</div>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-paper-plane"></i> Kirim Lamaran
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @else
                <div class="card shadow-sm text-center">
                    <div class="card-body py-5">
                        <i class="fas fa-user-lock fa-3x text-muted mb-3"></i>
                        <h5>Login untuk melamar pekerjaan ini</h5>
                        <a href="{{ route('login') }}" class="btn btn-primary mt-2">Login</a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection