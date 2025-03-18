<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // 如果是本地环境, 则注册这个服务提供者
        // 这样我们就可以在本地开发环境使用 lab404/laravel-impersonate 包
        // 在视图间共享用户数据
        if ($this->app->isLocal()) {
            View::composer('layouts.app', function ($view) {
                $view->with('users', User::all());
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
        \App\Models\Topic::observe(\App\Observers\TopicObserver::class);
        \App\Models\Link::observe(\App\Observers\LinkObserver::class);

        Paginator::useBootstrap();
    }
}
