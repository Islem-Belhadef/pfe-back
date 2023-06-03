<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Company;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Question;
use App\Models\Speciality;
use App\Models\University;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        University::factory()->create([
            'name' => 'University of Constantine 2 Abdelhamid Mehri',
            'address' => 'Ali Mendjli El-Khroub, Constantine',
        ]);

        Company::factory()->create([
            'name' => 'Ooredoo',
            'address' => 'Ali Mendjli El-Khroub, Constantine',
        ]);

        Faculty::factory()->create([
            'university_id' => 1,
            'name' => "Nouvelles Technologies de l'Information et Communication",
        ]);

        Department::factory()->create([
            'faculty_id' => 1,
            'name' => "Informatique Fondamentale et ses Applications",
        ]);
    }
}
