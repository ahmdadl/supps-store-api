<?php

use Modules\Users\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

it('is_no_access_for_non_guests', function () {
    postJson(route('api.auth.login'))
        ->assertUnauthorized();
});

test('guest_cannot_login_with_invalid_credentials', function () {
    asGuest();

    postJson(route('api.auth.login'), [
        'email' => 'test',
        'password' => 'test',
    ])->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('password');
});

test('guest_can_login_with_valid_credentials', function () {
    asGuest();

    $user = User::factory()->customer()->create();

    $response = postJson(route('api.auth.login'), [
        'email' => $user->email,
        'password' => $password = '123123123',
        'password_confirmation' => $password,
    ])->assertOk();

    $response->assertSee('name')
        ->assertSee('email')
        ->assertSee('access_token')
        ->assertSee('totals');
});

test('user_cannot_login', function () {
    $user = User::factory()->customer()->create();

    actingAs($user, 'customer');

    postJson(route('api.auth.login'), [
        'email' => $user->email,
        'password' => '123123123',
        'password_confirmation' => '123123123',
    ])->assertUnauthorized();
});
