<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    /**
     * Determine whether the user can delete the reply.
     *
     * 规则：
     * - 顶级回复：只有回复作者或话题作者可以删除
     * - 楼中楼回复：允许回复的作者、父回复的作者以及话题作者删除
     *
     * @param User $user
     * @param Reply $reply
     * @return bool
     */
    public function destroy(User $user, Reply $reply): bool
    {
        if ($reply->parent_id > 0) {
            // 楼中楼回复
            return $user->isAuthorOf($reply) ||
                ($reply->parent && $user->isAuthorOf($reply->parent)) ||
                ($reply->parent && $reply->parent->topic && $user->isAuthorOf($reply->parent->topic));
        }

        // 顶级回复
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
