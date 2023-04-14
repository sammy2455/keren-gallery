<?php

namespace Tests\Unit\Authentication;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_me_successfully(): void
    {
        // preparation
        $token = $this->login();

        // action
        $response = $this->postJson(route('authentication.me'), [], [
            "Authorization" => "Bearer " . $token,
        ]);

        // assertion
        $response
            ->assertOk()
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'updated_at',
                'created_at',
            ]);
    }

    private function login(): string
    {
        $this->seed(UserSeeder::class);

        $user = User::where('email', "sammy@email.com")->firstOrFail();

        return $user->createToken('auth_token')->plainTextToken;
    }
}
