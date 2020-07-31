<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    public function update(User $user, Topic $topic)
    {

         return $user->isAuthorOf($topic);//判断user_id是否是登入用户的user_id
//         return false;//判断user_id是否是登入用户的user_id
    }

    public function destroy(User $user, Topic $topic)
    {
        return $user->isAuthorOf($topic);

    }
}
