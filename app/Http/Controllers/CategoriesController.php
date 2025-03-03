<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Show topics list by category.
     *
     * @param Category $category
     * @param Request $request
     * @param Topic $topic
     * @return Factory|View|Application
     */
    public function show(Category $category, Request $request, Topic $topic): Factory|View|Application
    {
        $topics = $topic->withOrder($request->order)
            ->where('category_id', $category->id)
            ->with('user', 'category') // 使用 with 方法预加载防止 N+1 问题
            ->paginate(20);
        return view('topics.index', compact('topics', 'category'));
    }
}
