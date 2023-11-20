<?php

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ]);
});

it('validates only authenticated user may access the api', function () {
    // Act
    $response = asAdmin()->get('api/students');

    // Assert
    $response->assertStatus(200);
});

it('validates unauthenticated access will return error', function () {
    // Act
    $response = $this->get('api/students');

    // Assert
    $response->assertStatus(401);
});

it('shows validation error if invalid email used', function () {
    // Act
    $email = fake()->word();
    $response = asAdmin()->get('api/students?email='.$email);

    // Assert
    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('shows list of students and their courses', function () {
    // Arrange
    Student::factory(5)->create();
    Course::factory(10)->create();

    Course::all()->each(function ($course) {
        $students = Student::inRandomOrder()->take(rand(1, 5))->pluck('id');
        $course->students()->attach($students);
    });

    // Act
    $response = asAdmin()->get('api/students');

    // Assert
    $response
        ->assertStatus(200)
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data' => [
                0 => [
                    'id',
                    'name',
                    'email',
                    'courses',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

it('shows specific student and their courses', function () {
    // Arrange
    Student::factory(5)->create();
    Course::factory(10)->create();

    Course::all()->each(function ($course) {
        $students = Student::inRandomOrder()->take(rand(1, 5))->pluck('id');
        $course->students()->attach($students);
    });

    $student = Student::first();

    // Act
    $response = asAdmin()->get('api/students?email='.$student->email);

    // Assert
    $response
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonStructure([
            'data' => [
                0 => [
                    'id',
                    'name',
                    'email',
                    'courses',
                    'created_at',
                    'updated_at',
                ],
            ],
        ])
        ->assertJson([
            'data' => [
                0 => [
                    'email' => $student->email,
                ],
            ],
        ]);
});

function asAdmin()
{
    User::factory()->create([
        'email' => 'user@test.com',
        'password' => Hash::make('test'),
    ]);

    return test()->actingAs(
        User::first(),
    );
}
