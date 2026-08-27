<?php

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a visitor can create an account', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Guild Adventurer',
        'email' => 'adventurer@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs(User::where('email', 'adventurer@example.com')->first());
});

test('a registered user can sign in', function () {
    $user = User::factory()->create([
        'password' => 'password123',
    ]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('guests cannot access the guild hall', function () {
    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));
});
