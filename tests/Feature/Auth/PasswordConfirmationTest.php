<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Hashing\BcryptHasher;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('password.confirm'));

    $response->assertStatus(200);

    $response->assertInertia(function (Assert $page) {
        $page->component('auth/confirm-password');
    });
});


test('user can confirm their password', function () {

    $user = User::factory()->create([
        'password' => Hash::make('password'), 
    ]);

    $this->assertTrue(Hash::check('password', $user->password));

    $response = $this->actingAs($user)
        ->post(route('password.confirm.store'), [
            'password' => 'password',
        ]);

    $response->assertRedirect();
    // $response->assertSessionHasNoErrors();
});


test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'), // set known password
    ]);

    $response = $this->actingAs($user)->post(route('password.confirm.store'), [
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
});
