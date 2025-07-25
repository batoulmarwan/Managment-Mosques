<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        admin::create([
            'name'=>'Batoul',
            'email'=>'btwl46693@gmail.com',
            'password'=>Hash::make('1234gdQ5#'),
        ]);
        Admin::create([
            'name' => 'Dana',
            'email' => 'anglesharefy@gmail.com',
            'password' => Hash::make('1234gdQ5#'),
        ]);
    }
}
