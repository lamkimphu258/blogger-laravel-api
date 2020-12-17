<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private $route = '/api/v1/users';

    private $emailErrorStructure = [
        'message',
        'errors' => [
            'email'
        ]
    ];

    private $passwordErrorStructure = [
        'message',
        'errors' => [
            'password'
        ]
    ];

    private $emailPasswordErrorStructure = [
        'message',
        'errors' => [
            'email', 'password'
        ]
    ];

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRegisterSuccess()
    {
        $response = $this->json('POST', $this->route, [
            'email' => 'johndoe@email.com',
            'password' => 'password'
        ]);

        $response->assertStatus(204);
    }

    public function testRegisterWithEmptyEmail()
    {
        $response = $this->json('POST', $this->route, [
            'email' => '',
        ]);

        $response->assertJsonStructure($this->emailErrorStructure);
        $response->assertStatus(422);
    }

    public function testRegisterWithInvalidEmail()
    {
        $response = $this->json('POST', $this->route, [
            'email' => 'johndoeemail.com'
        ]);

        $response->assertJsonStructure($this->emailErrorStructure);
        $response->assertStatus(422);
    }

    public function testResgiterWithExistedEmail()
    {
        $this->json('POST', $this->route, [
            'email' => 'johndoe@email.com',
            'password' => 'password'
        ]);
        $response = $this->json('POST', $this->route, [
            'email' => 'johndoe@email.com',
            'password' => 'differencePassword'
        ]);

        $response->assertJsonStructure($this->emailErrorStructure);
        $response->assertStatus(422);
    }

    public function testRegisterWithEmptyPassword()
    {
        $response = $this->json('POST', $this->route, [
            'password' => ''
        ]);

        $response->assertJsonStructure($this->passwordErrorStructure);
        $response->assertStatus(422);
    }

    public function testRegisterWithPasswordLengthLowerThanSix()
    {
        $response = $this->json('POST', $this->route, [
            'password' => 'pass'
        ]);

        $response->assertJsonStructure($this->passwordErrorStructure);
        $response->assertStatus(422);
    }

    public function testRegisterWithInvalidEmailAndPassword() {
        $response = $this->json('POST', $this->route, [
            'email' => 'johndoeemail.com',
            'password' => 'pass'
        ]);

        $response->assertJsonStructure($this->emailPasswordErrorStructure);
        $response->assertStatus(422);
    }
}
