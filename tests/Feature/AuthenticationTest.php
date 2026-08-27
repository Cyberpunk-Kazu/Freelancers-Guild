<?php

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

test('a visitor can create an account', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Guild Adventurer',
        'email' => 'adventurer@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs(User::where('email', 'adventurer@example.com')->first());
});

test('an account requires a valid human name', function () {
    $response = $this->from(route('register'))->post(route('register.store'), [
        'name' => '123_troll',
        'email' => 'adventurer@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect(route('register'));
    $response->assertSessionHasErrors('name');
    $this->assertDatabaseMissing('users', ['email' => 'adventurer@example.com']);
});

test('an account rejects inappropriate names', function () {
    $response = $this->from(route('register'))->post(route('register.store'), [
        'name' => 'Guild Fuck',
        'email' => 'adventurer@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect(route('register'));
    $response->assertSessionHasErrors('name');
    $this->assertDatabaseMissing('users', ['email' => 'adventurer@example.com']);
});

test('the name policy rejects inappropriate words regardless of capitalization', function () {
    expect(\App\Domain\Users\Rules\ValidGuildName::passes('NIGGA'))->toBeFalse();
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

test('a user can request a password reset link', function () {
    Notification::fake();
    $user = User::factory()->create();

    $response = $this->post(route('password.email'), ['email' => $user->email]);

    $response->assertSessionHas('status', __('passwords.sent'));
    Notification::assertSentTo($user, ResetPassword::class);
});
