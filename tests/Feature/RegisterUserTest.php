<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    private $route = '/api/v1/register';

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

    public function test_it_checks_empty_email()
    {
        $response = $this->json('POST', $this->route, [
            'email' => '',
        ]);

        $response->assertJsonStructure($this->emailErrorStructure);
        $response->assertStatus(422);
    }

    public function test_it_checks_invalid_email()
    {
        $response = $this->json('POST', $this->route, [
            'email' => 'johndoeemail.com'
        ]);

        $response->assertJsonStructure($this->emailErrorStructure);
        $response->assertStatus(422);
    }

    public function test_it_checks_existed_email()
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

    public function test_it_checks_empty_password()
    {
        $response = $this->json('POST', $this->route, [
            'password' => ''
        ]);

        $response->assertJsonStructure($this->passwordErrorStructure);
        $response->assertStatus(422);
    }

    public function test_it_checks_password_length_lower_than_6()
    {
        $response = $this->json('POST', $this->route, [
            'password' => 'pass'
        ]);

        $response->assertJsonStructure($this->passwordErrorStructure);
        $response->assertStatus(422);
    }

    public function test_it_checks_invalid_email_and_password()
    {
        $response = $this->json('POST', $this->route, [
            'email' => 'johndoeemail.com',
            'password' => 'pass'
        ]);

        $response->assertJsonStructure($this->emailPasswordErrorStructure);
        $response->assertStatus(422);
    }

    public function test_it_registers_successfully()
    {
        $response = $this->json('POST', $this->route, [
            'email' => 'johndoe@email.com',
            'password' => 'password'
        ]);

        $response->assertStatus(204);
    }
}
