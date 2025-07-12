<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'id' => Str::uuid(),
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@aksamedia.com',
            'phone' => '081234567890',
            'password' => Hash::make('siadmin123'),
        ]);
    }
}
