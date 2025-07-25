<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::select(
            'id',
            'full_name',
            'mother_name',
            'old',
            'mynumber',
            'addess',
            'ago_work',
            'studing',
            'email'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Full Name',
            'Mother Name',
            'Age',
            'My Number',
            'Address',
            'Previous Work',
            'Studying',
            'Email'
        ];
    }
}

