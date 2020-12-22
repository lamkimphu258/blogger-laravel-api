<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class TopicTest extends TestCase
{
    private $route = '/api/v1/topics';

    private $topicErrorStructure = [
        'message',
        'errors' => [
            'title'
        ]
    ];

    private $user;

    private $topic;

    private $post;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->topic = Topic::factory()->create(['user_id' => $this->user->id]);
        $this->post = Post::factory()->times(5)->create(['topic_id' => $this->topic->id]);

        Artisan::call('db:seed');
    }

    public function test_unauthenticated_user_cannot_store_post()
    {
        $response = $this->postJson($this->route, [
            "title" => "This is new title"
        ]);

        $response->assertStatus(401);
    }

    public function test_it_checks_empty_title()
    {
        $response = $this->actingAs($this->user, 'api')->postJson($this->route, [
            'title' => ''
        ]);

        $response->assertJsonStructure($this->topicErrorStructure);
        $response->assertStatus(422);
    }

    public function test_it_checks_title_longer_than_255_characters()
    {
        $response = $this->actingAs($this->user, 'api')->postJson($this->route, [
            "title" => Str::random(256)
        ]);

        $response->assertJsonStructure($this->topicErrorStructure);
        $response->assertStatus(422);
    }

    public function test_authenticated_user_can_create_post()
    {
        $response = $this->actingAs($this->user, 'api')->postJson($this->route, [
            "title" => "This is a new title"
        ]);

        $response->assertStatus(204);
    }

    public function test_it_return_all_topics()
    {
        $response = $this->getJson($this->route);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'created_at',
                    'posts' => [
                        '*' => [
                            'id',
                            'body'
                        ]
                    ],
                    'user' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);
    }

    public function test_it_return_topic_with_specific_id()
    {
        $response = $this->getJson($this->route.'/'.$this->topic->id);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'created_at',
                'posts' => [
                    '*' => [
                        'id',
                        'body'
                    ]
                ],
                'user' => [
                    'id',
                    'name',
                    'email'
                ]
            ]
        ]);
        $response->assertStatus(200);
    }

    public function test_it_checks_non_existence_topic_id()
    {
        $response = $this->getJson($this->route.'/'.Str::uuid());

        $response->assertStatus(404);
    }
}
