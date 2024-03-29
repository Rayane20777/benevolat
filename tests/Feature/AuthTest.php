<?php

namespace Tests\Feature;


use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

use Tests\TestCase;


class AuthTest extends TestCase
{

    use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function testUserIsCreatedSuccessfully()
    {
        $payload = [
            'name' => 'Jojkhjn',
            'email' => 'johjkjhjhfdsjh.doe@example.com',
            'password' => 'paGHJ67è_ssword', // Don't hash the password here
            'role' => 'benevole',
        ];

        $this->json('post', 'api/register', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status',
                'message',
                'user'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'role' => $payload['role'],
        ]);
    }
}

