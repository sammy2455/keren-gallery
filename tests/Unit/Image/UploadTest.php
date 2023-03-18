<?php

namespace Tests\Unit\Image;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_image_upload_successfully(): void
    {
        // preparation
        [$authenticatedUser, $token] = $this->preparation();
        $file = new UploadedFile(
            $this->faker->image(),
            "avatar.png",
            'image/png',
            null,
            true // very important
        );

        // action
        $response = $this->post(route('image.upload'), [
            "image" => $file,
        ], [
            "Authorization" => "Bearer " . $token,
        ]);

        // assertion
        $response
            ->assertOk()
            ->assertJsonStructure([
                "success",
                "id",
                "internal_url",
                "url",
            ]);
    }

    private function preparation(): array
    {
        $this->seed(UserSeeder::class);

        $authenticatedUser = User::query()->where('email', 'sammy@email.com')->first();

        $token = $authenticatedUser->createToken('auth_token')->plainTextToken;

        return [$authenticatedUser, $token];
    }
}
