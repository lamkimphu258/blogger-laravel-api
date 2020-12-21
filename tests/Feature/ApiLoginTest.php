<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApiLoginTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('example')
        ]);
    }

    public function test_it_returns_access_token()
    {
        $client = DB::table('oauth_clients')->where('provider', 'users')->first();

        $body = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => 'user@example.com',
            'password' => 'example',
            'scope' => '*'
        ];
        $this->post('/oauth/token', $body)
            ->assertStatus(200)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);

    }
}
