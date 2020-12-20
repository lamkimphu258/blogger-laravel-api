<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicRequest;
use App\Models\Topic;
use Illuminate\Support\Facades\Log;
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
}
