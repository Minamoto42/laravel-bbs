<?php

namespace App\Observers;

use App\Models\Reply;
use App\Models\User;
use App\Notifications\TopicReplied;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    /**
     * When the reply is created, update the reply count of the topic.
     *
     * @param Reply $reply
     * @return void
     */
    public function created(Reply $reply): void
    {
        $reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->save();
        // Notify the author of the topic if the reply is not from the author.
        // 给「回复(replies)」的「话题(topics)」的「作者(users)」发送通知
        $reply->topic->user->notify(new TopicReplied($reply));
    }

    /**
     * When creating the reply, clean the content's HTML tags.
     *
     * @param Reply $reply
     * @return void
     * @throws ValidationException
     */
    public function creating(Reply $reply): void
    {
        $reply->content = clean($reply->content, 'user_topic_body');

        $validator = Validator::make($reply->toArray(), [
            'content' => 'required|min:2',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
