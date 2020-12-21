<?php

namespace Tests\Feature;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostTest extends TestCase
{
    private $user;
    private $topic;
    private $route;

    private $postErrorStructure = [
        'message',
        'errors' => [
            'body'
        ]
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->topic = Topic::factory()->create(['user_id' => $this->user->id]);
        $this->route = "/api/v1/topics/".$this->topic->id."/posts";
    }

    public function test_unauthenticated_user_cannot_create_a_post()
    {
        $response = $this->json('POST', $this->route, [
            'body' => 'This is new post'
        ]);

        $response->assertStatus(401);
    }

    public function test_it_checks_empty_body()
    {
        $response = $this->actingAs($this->user, 'api')->json('POST', $this->route, [
            'body' => ''
        ]);

        $response->assertJsonStructure($this->postErrorStructure);
        $response->assertStatus(422);
    }

    public function test_it_checks_body_length_lower_than_255_characters()
    {
        $response = $this->actingAs($this->user, 'api')->json('POST', $this->route, [
            'body' => Str::random(256)
        ]);

        $response->assertJsonStructure($this->postErrorStructure);
        $response->assertStatus(422);
    }

    public function test_authenticated_user_can_create_post()
    {
        $response = $this->actingAs($this->user, 'api')->json('POST', $this->route, [
            'body' => 'This is new body'
        ]);

        $response->assertStatus(204);
    }
}
