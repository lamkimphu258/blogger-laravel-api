<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function store(StoreTopicRequest $request)
    {
        $topic = new Topic();
        $topic->id = Str::uuid();
        $topic->title = $request->get('title', $topic->title);
        $topic->user()->associate($request->user());
        $topic->save();

        return response(null, 204);
    }

    public function index()
    {
        $topics = Topic::all();
        return TopicResource::collection($topics);
    }

    public function show($topicId)
    {
        $topic = Topic::find($topicId);
        if (!$topic) {
            return response(null, 404);
        }

        return new TopicResource($topic);
    }

    public function update(UpdateTopicRequest $request, $topicId)
    {
    }
}
