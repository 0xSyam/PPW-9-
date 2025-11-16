<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ApplicationsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Application::with('user', 'job')->get()->map(function ($application) {
            return [
                'Applicant Name' => $application->user->name,
                'Job Title' => $application->job->title,
                'CV Path' => $application->cv,
                'Status' => $application->status ?? 'Pending',
                'Applied At' => $application->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Applicant Name',
            'Job Title',
            'CV Path',
            'Status',
            'Applied At',
        ];
    }
}
