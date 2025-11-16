<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use App\Exports\ApplicationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function store(Request $request, $jobId)
    {
        $request->validate([
            'cv' => 'required|mimes:pdf|max:2048',
        ]);

        $cvPath = $request->file('cv')->store('cvs', 'public');

        Application::create([
            'user_id' => auth()->id(),
            'job_id' => $jobId,
            'cv' => $cvPath,
        ]);

        return back()->with('success', 'Lamaran berhasil dikirim!');
    }

    public function index($jobId)
    {
        $applications = Application::with('user', 'job')->where('job_id', $jobId)->get();
        return view('applications.index', compact('applications'));
    }

    public function myApplications()
    {
        $applications = Application::with('job')->where('user_id', auth()->id())->get();
        return view('applications.my', compact('applications'));
    }

    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:Accepted,Rejected',
        ]);

        $application->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status lamaran berhasil diperbarui!');
    }

    public function download(Application $application)
    {
        return Storage::disk('public')->download($application->cv);
    }

    public function export()
    {
        return Excel::download(new ApplicationsExport, 'applications.xlsx');
    }

}
