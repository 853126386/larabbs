<?php

namespace App\Transformers;

use App\Models\Image;
use App\Models\Reply;
use League\Fractal\TransformerAbstract;
class  ReplyTransformer extends TransformerAbstract{

    protected  $availableIncludes=['user','reply'];


    public function transform(Reply $reply)
    {
        return [
            'id' => $reply->id,
            'content' => $reply->content,
            'user_id' => (int) $reply->user_id,
            'topic_id' => (int) $reply->topic_id,
            'created_at' => (string) $reply->created_at,
            'updated_at' => (string) $reply->updated_at,
        ];
    }

 /*   public function includeUser(reply $reply)
    {
        return $this->item($reply->user,new UserTransformer());
    }
    public function includeTopic(reply $reply)
    {
        return $this->item($reply->Topic,new CategoryTransformer());
    }*/

}
