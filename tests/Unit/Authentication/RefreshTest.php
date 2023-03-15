<?php

namespace Tests\Unit\Authentication;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RefreshTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_refresh_successfully(): void
    {
        // preparation
        $token = $this->login();

        // action
        $response = $this->postJson(route('authentication.refresh'), [], [
            "Authorization" => "Bearer " . $token,
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

    private function login(): string
    {
        $this->seed(UserSeeder::class);

        $user = User::where('email', "john.doe@email.com")->firstOrFail();

        return $user->createToken('auth_token')->plainTextToken;
    }
}
