<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Models\Topic;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TopicRequest;

class TopicsController extends Controller
{
    /**
     * TopicsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Show topics list.
     * @param Request $request
     * @param Topic $topic
     *
     * @return Factory|View|Application
     */
    public function index(Request $request, Topic $topic): Factory|View|Application
    {
        $topics = $topic->withOrder($request->order)
            ->with('user', 'category') // 使用 with 方法预加载防止 N+1 问题
            ->paginate(20);
        return view('topics.index', compact('topics'));
    }

    /**
     * Show topic detail.
     *
     * @param Topic $topic
     * @return Factory|View|Application
     */
    public function show(Topic $topic): Factory|View|Application
    {
        return view('topics.show', compact('topic'));
    }

    /**
     * Display create topic form.
     *
     * @param Topic $topic
     * @return Factory|View|Application
     */
    public function create(Topic $topic): Factory|View|Application
    {
        return view('topics.create_and_edit', compact('topic'));
    }

    /**
     * Store topic.
     *
     * @param TopicRequest $request
     * @return RedirectResponse
     */
    public function store(TopicRequest $request): RedirectResponse
    {
        $topic = Topic::create($request->all());
        return redirect()->route('topics.show', $topic->id)->with('message', 'Created successfully.');
    }

    /**
     * Display edit topic form.
     *
     * @param Topic $topic
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(Topic $topic): View|Factory|Application
    {
        $this->authorize('update', $topic);
        return view('topics.create_and_edit', compact('topic'));
    }

    /**
     * Update topic.
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(TopicRequest $request, Topic $topic): RedirectResponse
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
    }

    /**
     * Destroy topic.
     *
     * @param Topic $topic
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Topic $topic): RedirectResponse
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
    }
}
