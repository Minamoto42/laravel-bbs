<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * 应用中模型与策略的映射
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Reply::class => \App\Policies\ReplyPolicy::class,
        \App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        // 其他模型策略映射...
    ];

    /**
     * 注册所有认证/授权服务。
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // 使用动态策略名称推测的回调函数，确保当没有显式映射时也能找到策略类
        Gate::guessPolicyNamesUsing(function ($modelClass) {
            return 'App\Policies\\' . class_basename($modelClass) . 'Policy';
        });

        // 限制只有站长才可以访问 Horizon 控制面板
        Horizon::auth(function ($request) {
            // 只有站长才能访问 Horizon 控制台
            return Auth::check() && Auth::user()->hasRole('Founder');
        });
    }
}
