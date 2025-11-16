@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Status Lamaran Saya
                    </h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($applications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Lowongan</th>
                                        <th>Status</th>
                                        <th>Tanggal Lamar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                        <tr>
                                            <td>
                                                <strong>{{ $application->job->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $application->job->company }} - {{ $application->job->location }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $application->status === 'Accepted' ? 'success' : ($application->status === 'Rejected' ? 'danger' : 'warning') }} fs-6 px-3 py-2">
                                                    {{ $application->status }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $application->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td>
                                                <a href="{{ Storage::disk('public')->url($application->cv) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-download me-1"></i> Download CV
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox fa-5x text-muted mb-4 opacity-50"></i>
                            <h5 class="text-muted mb-3">Belum ada lamaran</h5>
                            <p class="text-muted mb-4">Mulai lamar lowongan untuk melihat status di sini.</p>
                            <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i> Cari Lowongan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection