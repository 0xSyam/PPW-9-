<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobsTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['title', 'description', 'location', 'company', 'salary', 'job_type'],
            // Sample row
            ['Sample Job', 'Sample description', 'Jakarta', 'Sample Company', '9000000', 'Full-time'],
        ];
    }

    public function headings(): array
    {
        return [
            'Title',
            'Description',
            'Location',
            'Company',
            'Salary',
            'Job Type (Full-time/Part-time)',
        ];
    }
}