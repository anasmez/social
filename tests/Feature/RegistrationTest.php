<?php

namespace Tests\Feature;

use App\User;
use Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_register()
    {
        $this->withoutExceptionHandling();
        $userData = [
            'name' => 'AnasMeziani',
            'first_name' => 'Anas',
            'last_name' => 'Meziani',
            'email' => 'anas@email.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $response = $this->post(route('register'), $userData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'name' => 'AnasMeziani',
            'first_name' => 'Anas',
            'last_name' => 'Meziani',
            'email' => 'anas@email.com',
        ]);

        $this->assertTrue(
            Hash::check('secret', User::first()->password), 'The password needs to be hashed'
        );
    }
}
