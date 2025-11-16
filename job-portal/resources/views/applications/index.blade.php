@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="fas fa-users me-2"></i>
            Pelamar untuk: {{ $applications->first()->job->title ?? 'Lowongan' }}
        </h2>
        <a href="{{ route('applications.export') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>

    @if($applications->count() > 0)
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Applicant Name</th>
                            <th>Job Title</th>
                            <th>CV</th>
                            <th>Status</th>
                            <th>Applied At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $index => $application)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $application->user->name }}</strong>
                                    <br><small class="text-muted">{{ $application->user->email }}</small>
                                </td>
                                <td>{{ $application->job->title }}</td>
                                <td>
                                    <a href="{{ route('applications.download', $application) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                        <i class="fas fa-download"></i> Download CV
                                    </a>
                                </td>
                                <td>
                                    @php $status = $application->status ?? 'Pending'; @endphp
                                    <span class="badge bg-{{ $status === 'Accepted' ? 'success' : ($status === 'Rejected' ? 'danger' : 'warning') }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td>{{ $application->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <form action="{{ route('applications.update', $application) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Accepted">
                                            <button type="submit" class="btn btn-success btn-sm" {{ $application->status === 'Accepted' ? 'disabled' : '' }}>
                                                <i class="fas fa-check"></i> Accept
                                            </button>
                                        </form>
                                        <form action="{{ route('applications.update', $application) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Rejected">
                                            <button type="submit" class="btn btn-danger btn-sm" {{ $application->status === 'Rejected' ? 'disabled' : '' }}>
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-user-slash fa-5x text-muted mb-4"></i>
            <h4>Belum ada lamaran untuk lowongan ini</h4>
            <a href="{{ route('jobs.index') }}" class="btn btn-primary">Kembali ke Lowongan</a>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Lowongan
        </a>
    </div>
</div>
@endsection