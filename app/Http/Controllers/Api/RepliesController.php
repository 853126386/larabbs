<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ReplyRequest;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;

class RepliesController extends Controller
{
    //

    public function store(ReplyRequest $request,Reply $reply,Topic $topic)
    {
        $reply->content=$request->content;
        $reply->user()->associate($this->user());
        $reply->topic()->associate($topic);
        $reply->save();
        return $this->response->item($reply,new ReplyTransformer())->setStatusCode(201);

    }
}
