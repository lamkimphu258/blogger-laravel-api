<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function store(StorePostRequest $request, $topicId)
    {
        $post = new Post;
        $post->id = Str::uuid();
        $post->body = $request->get('body', $post->body);
        $post->user()->associate($request->user());

        $topic = Topic::find($topicId);
        $topic->posts()->save($post);

        return response(null, 204);
    }
}
