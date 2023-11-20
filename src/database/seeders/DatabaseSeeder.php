<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed students and courses
        Student::factory(50)->create();
        Course::factory(15)->create();

        // Seed the pivot table
        Course::all()->each(function ($course) {
            $students = Student::inRandomOrder()->take(rand(1, 50))->pluck('id');
            $course->students()->attach($students);
        });
    }
}
