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

    public function index(Topic $topic, Request $request)
    {
        app(\Dingo\Api\Transformer\Factory::class)->disableEagerLoading();

        $replies=$topic->replies()->paginate(20);

        if ($request->include) {
            $replies->load(explode(',', $request->include));
        }
        return $this->response->paginator($replies,new ReplyTransformer());
    }

    public function userIndex(User $user, Request $request)
    {
        app(\Dingo\Api\Transformer\Factory::class)->disableEagerLoading();

        $replies = $user->replies()->paginate(20);

        if ($request->include) {
            $replies->load(explode(',', $request->include));
        }

        return $this->response->paginator($replies, new ReplyTransformer());
    }

    public function store(ReplyRequest $request,Reply $reply,Topic $topic)
    {
        $reply->content=$request->content;
        $reply->user()->associate($this->user());
        $reply->topic()->associate($topic);
        $reply->save();
        return $this->response->item($reply,new ReplyTransformer())->setStatusCode(201);
    }

    public function destroy(Topic $topic,Reply $reply)
    {
        $this->authorize('destroy',$reply);

        if($topic->id!=$reply->topic_id){
            return $this->response->errorBadRequest();
        }

        $reply->delete();

        return $this->response->noContent();

    }
}

