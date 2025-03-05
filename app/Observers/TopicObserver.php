<?php

namespace App\Observers;

use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    /**
     * When saving the topic, clean the body and make the excerpt.
     *
     * @param Topic $topic
     * @return void
     */
    public function saving(Topic $topic): void
    {
        $topic->body = clean($topic->body, 'user_topic_body');
        $topic->excerpt = make_excerpt($topic->body);
    }
    /**
     * When creating the topic, generate the slug.
     *
     * @param Topic $topic
     * @return void
     */
    public function created(Topic $topic): void
    {
        $topic->slug = env('APP_URL') . '/topics/' . $topic->id;
        $topic->save();
    }
}
