<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    /**
     * Determine whether the user can update the topic.
     *
     * @param User $user
     * @param Topic $topic
     * @return bool
     */
    public function update(User $user, Topic $topic): bool
    {
        return $user->isAuthorOf($topic);
    }

    /**
     * Determine whether the user can delete the topic.
     *
     * @param User $user
     * @param Topic $topic
     * @return bool
     */
    public function destroy(User $user, Topic $topic): bool
    {
        return $user->isAuthorOf($topic);
    }
}
