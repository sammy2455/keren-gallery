<?php

namespace Tests\Unit\Authentication;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_logout_successfully(): void
    {
        // preparation
        $token = $this->login();

        // action
        $response = $this->postJson(route('authentication.logout'), [], [
            "Authorization" => "Bearer " . $token,
        ]);

        // assertion
        $response
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
    }

    private function login(): string
    {
        $this->seed(UserSeeder::class);

        $user = User::where('email', "sammy@email.com")->firstOrFail();

        return $user->createToken('auth_token')->plainTextToken;
    }
}
