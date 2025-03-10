<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    /**
     * Determine whether the user can delete the reply.
     * 我们规定只有「话题的作者」或者「回复的作者」才能删除回复
     * 话题的作者是 reply.topic.user_id
     * 回复的作者是 reply.user_id
     *
     * @param User $user
     * @param Reply $reply
     * @return bool
     */
    public function destroy(User $user, Reply $reply): bool
    {
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
