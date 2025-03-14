<?php

use Illuminate\Support\Facades\Route;

/**
 * Get the route name for the CSS class.
 *
 * @return array|string|null
 */
function route_class(): array|string|null
{
    return str_replace('.', '-', Route::currentRouteName());
}

/**
 * Get the active class for the current route.
 *
 * @param int $category_id
 * @return string
 */
function category_nav_active(int $category_id): string
{
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}

/**
 * Make excerpt for the topic body.
 *
 * @param string $value
 * @param int $length
 * @return \Illuminate\Support\Stringable|mixed
 */
function make_excerpt(string $value, int $length = 200): mixed
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str()->limit($excerpt, $length);
}

/**
 * Check if the current reply's child is collapsed.
 *
 * @param int $reply_id
 * @return bool
 */
function collapse(int $reply_id): bool
{
    return request()->query('reply_id') == $reply_id && request()->query('child');
}

/**
 * Generate a model link for the admin panel.
 *
 * @param $title
 * @param $model
 * @return string
 */
function model_admin_link($title, $model): string
{
    return model_link($title, $model, 'admin');
}

/**
 * Generate a model link.
 *
 * @param $title
 * @param $model
 * @param string $prefix
 * @return string
 */
function model_link($title, $model, string $prefix = ''): string
{
    // 获取模型的复数形式蛇形命名
    $model_name = model_plural_name($model);

    // 初始化前缀
    $prefix = $prefix ? "/$prefix/" : '/';

    // 使用站点 URL 拼接链接
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    // 拼接 HTML a 标签, 并返回
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

/**
 * Get the plural name of the model.
 *
 * @param $model
 * @return string
 */
function model_plural_name($model): string
{
    // 从实体中获取完整的类名, 例如: App\Models\User
    $full_class_name = get_class($model);

    // 获取基础类名, 例如: 传入 App\Models\User 则返回 User
    $class_name = class_basename($model);

    // 蛇形命名, 例如: 传入 User 则返回 users, 传入 UserCategory 则返回 user_categories
    $snake_case_name = str()->snake($class_name);

    // 获取子串的复数形式, 例如: 传入 User 则返回 users, 传入 UserCategory 则返回 user_categories
    return str()->plural($snake_case_name);
}
