<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user has the specified permission.
     * before 方法会在其他授权策略方法之前调用, 如果 before 方法返回 true 则代表通过授权
     *
     * @return true|void
     */
    public function before($user, $ability)
	{
        // 如果用户拥有管理内容的权限, 即授权通过
        if ($user->can('manage_contents')) {
            return true;
        }
	}
}
