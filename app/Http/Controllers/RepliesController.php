<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a reply for the topic.
     *
     * 支持两种回复：
     * - 顶级回复：直接回复话题（parent_id = 0, topic_id 为当前话题 ID）
     * - 楼中楼回复：回复某条回复（parent_id > 0, topic_id 设置为 0）
     *
     * @param  ReplyRequest  $request
     * @param  Reply         $reply
     * @return RedirectResponse
     */
    public function store(ReplyRequest $request, Reply $reply): RedirectResponse
    {
        $reply->message = $request->message;
        $reply->user_id = Auth::id();

        // 判断是否为楼中楼回复：如果请求中传入了 parent_id 且大于 0
        if ($request->filled('parent_id') && $request->parent_id > 0) {
            $reply->parent_id = $request->parent_id;
            // 楼中楼回复的 topic_id 按需求设置为 0
            $reply->topic_id = 0;
        } else {
            // 顶级回复：回复当前话题
            $reply->topic_id = $request->topic_id;
            $reply->parent_id = 0;
        }

        $reply->save();

        // 根据回复类型决定跳转地址：
        // 如果是楼中楼回复，则跳转到父回复所属话题的页面；否则跳转到当前回复所属话题页面
        if ($reply->parent_id > 0 && $reply->parent) {
            $slug = $reply->parent->topic->slug;
        } else {
            $slug = $reply->topic->slug;
        }

        return redirect()->to($slug . '#reply' . $reply->id)
            ->with('success', 'Reply created successfully.');
    }

    /**
     * Delete the reply.
     *
     * 删除权限由 ReplyPolicy 控制：
     * - 楼中楼回复：允许回复作者、父回复作者以及话题作者删除
     * - 顶级回复：允许回复作者或话题作者删除
     *
     * @param  Reply  $reply
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Reply $reply): RedirectResponse
    {
        $this->authorize('destroy', $reply);
        $reply->delete();

        // 删除后跳转回话题页面
        return redirect()->to($reply->topic->slug)
            ->with('success', 'Deleted successfully.');
    }
}
