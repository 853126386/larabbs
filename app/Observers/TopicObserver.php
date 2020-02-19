<?php

namespace App\Observers;

use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    /**
 * 更新和创建的时候都会触发
 * @param Topic $topic
 */
    public function saving(Topic $topic)
    {
        $topic->excerpt = make_excerpt($topic->body);
    }

}
