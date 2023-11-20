<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $subjects = ['Mathematics', 'Physics', 'Computer Science', 'Biology', 'History', 'Literature'];
        $adjectives = ['Introduction to', 'Advanced', 'Fundamentals of', 'Principles of', 'Applied'];

        $name = fake()->randomElement($adjectives).' '.fake()->randomElement($subjects);

        return [
            'name' => rtrim($name, '.'),
        ];
    }
}
