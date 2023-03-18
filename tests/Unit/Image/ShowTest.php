<?php

namespace Tests\Unit\Image;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_image_show_successfully(): void
    {
        // preparation
        [$id] = $this->preparation();

        // action
        $response = $this->get(route('image.show', [
            'id' => $id,
        ]));

        // assertion
        $response->assertOk();
    }

    private function preparation(): array
    {
        $this->seed(UserSeeder::class);

        $authenticatedUser = User::query()->where('email', 'sammy@email.com')->first();
        $token = $authenticatedUser->createToken('auth_token')->plainTextToken;

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

        $id = $response['id'];

        return [$id];
    }
}
