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
        $reply->topic->updateReplyCount();

        // Notify the author of the topic if the reply is not from the author.
        // 给「回复(replies)」的「话题(topics)」的「作者(users)」发送通知
        if ($reply->user_id !== $reply->topic->user_id) {
            $reply->topic->user->notify(new TopicReplied($reply));
        }
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
        $reply->message = clean($reply->message, 'user_topic_body');

        // 在这里我们重新去验证回复的内容, 因为可能遇到 xss 攻击的问题我们过滤完了之后内容为空
        // <script>alert('This is dangerous!!!!')</script>
        $validator = Validator::make($reply->toArray(), [
            'message' => 'required|min:2',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * When the reply is deleted, update the reply count of the topic.
     *
     * @param Reply $reply
     * @return void
     */
    public function deleted(Reply $reply): void
    {
        $reply->topic->updateReplyCount();
    }
}
