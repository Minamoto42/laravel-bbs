<?php

namespace App\Http\Controllers;

use App\Models\Reply;
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
     * @param ReplyRequest $request
     * @param Reply $reply
     * @return RedirectResponse
     */
    public function store(ReplyRequest $request, Reply $reply): RedirectResponse
    {
        $child = false;
        if ($request->parent_id) {
            $reply->parent_id = $request->parent_id;
            $child = true;
        }

        $reply->message = $request->message;
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->topic_id;
        $reply->save();

        return redirect()
            ->to($reply->topic->slug . "?child=$child&reply_id=$reply->parent_id#reply$reply->id")
            ->with('success', 'Reply created successfully.');
    }

    /**
     * Delete the reply.
     *
     * @param Reply $reply
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Reply $reply): RedirectResponse
    {
        // If the reply has children, do not delete it.
        if ($reply->child()->exists()) {
            return back()->with('danger', 'This reply has children, please delete them first.');
        }

        // For collapsed reply's child item.
        $child = false;
        if ($reply->parent_id) {
            $child = true;
        }

        $this->authorize('destroy', $reply);
        $reply->delete();

        return redirect()->to($reply->topic->slug . "?child=$child&reply_id=$reply->parent_id#reply$reply->id")->with('success', 'Deleted successfully.');
    }
}
