<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ]);

    $this->user = User::factory()->create([
        'email' => 'john@wick.com',
        'password' => Hash::make('password'),
    ]);
});

it('shows validation error if mandatory fields are empty', function () {
    // Act
    $response = $this->postJson('api/login', [
        'email' => '',
        'password' => '',
    ]);

    // Assert
    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

it('shows validation error if invalid credentials used', function () {
    // Act
    $response = $this->postJson('api/login', [
        'email' => 'some@email.com',
        'password' => 'password',
    ]);

    // Assert
    $response
        ->assertStatus(401)
        ->assertJson([
            'message' => 'Credentials do not match',
        ]);
});

it('returns correct user data and token on success login', function () {
    // Act
    $response = $this->postJson('api/login', [
        'email' => 'john@wick.com',
        'password' => 'password',
    ]);

    // Assert
    $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data.token')
            ->has('data.user', fn (AssertableJson $json) => $json->where('id', $this->user->id)
                ->where('name', $this->user->name)
                ->where('email', fn (string $email) => str($email)->is($this->user->email))
                ->missing('password')
                ->etc()
            )
            ->etc()
        );
});

it('returns error on logout for non authenticated user', function () {
    // Act
    $response = $this->postJson('api/logout');

    // Assert
    $response
        ->assertStatus(401)
        ->assertJson([
            'message' => 'Unauthenticated.',
        ]);
});

it('returns success message on logout for authenticated user', function () {
    // Arrange
    Sanctum::actingAs(
        User::first(),
    );

    // Act
    $response = $this->postJson('api/logout');

    // Assert
    $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn (AssertableJson $json) => $json
                ->where('message', 'You have successfully been logged out and your token has been removed')
                ->etc()
            )
            ->etc()
        );
});
