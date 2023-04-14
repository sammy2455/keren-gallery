<?php

namespace Tests\Unit\Authentication;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_login_successfully(): void
    {
        // preparation
        $this->seed(UserSeeder::class);

        // action
        $response = $this->postJson(route('authentication.login'), [
            "email" => "sammy@email.com",
            "password" => "password",
        ]);

        // assertion
        $response
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'token',
                'token_type',
                'expires_in',
            ]);
    }
}
