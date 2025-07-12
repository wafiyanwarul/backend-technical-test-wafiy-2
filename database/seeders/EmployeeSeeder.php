<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Division;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = Division::pluck('id')->toArray();

        $employees = [
            [
                'id' => Str::uuid(),
                'name' => 'John Doe',
                'phone' => '081111111111',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Developer',
                'image' => null, // Image will be added via API
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Jane Smith',
                'phone' => '082222222222',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Designer',
                'image' => null,
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
