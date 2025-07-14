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
                'name' => 'Ahmad Fauzan',
                'phone' => '081234567891',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Developer',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Siti Maulida',
                'phone' => '081234567892',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Designer',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Muhammad Rizky',
                'phone' => '081234567893',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Product Manager',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Nur Aini',
                'phone' => '081234567894',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'UI/UX Designer',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Fadlan Alfarizi',
                'phone' => '081234567895',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Backend Developer',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Aisyah Zahra',
                'phone' => '081234567896',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Frontend Developer',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Hasan Alwi',
                'phone' => '081234567897',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Data Analyst',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Raihan Ramadhan',
                'phone' => '081234567898',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Mobile Developer',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Khalifah Naufal',
                'phone' => '081234567899',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'System Analyst',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Hanif Maulana',
                'phone' => '081234567800',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'DevOps Engineer',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Syifa Lathifah',
                'phone' => '081234567801',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Project Manager',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Zaidan Hakim',
                'phone' => '081234567802',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'QA Engineer',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Nadira Khalila',
                'phone' => '081234567803',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Business Analyst',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Ihsan Firdaus',
                'phone' => '081234567804',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Scrum Master',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Yumna Nabila',
                'phone' => '081234567805',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Content Writer',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Akmal Ihsan',
                'phone' => '081234567806',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'IT Support',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Farah Hanifah',
                'phone' => '081234567807',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'QA Tester',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Rizqan Hidayat',
                'phone' => '081234567808',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Software Engineer',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Zahira Khairunnisa',
                'phone' => '081234567809',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Marketing Analyst',
                'image' => null,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Hafidz Wildan',
                'phone' => '081234567810',
                'division_id' => $divisions[array_rand($divisions)],
                'position' => 'Fullstack Developer',
                'image' => null,
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
