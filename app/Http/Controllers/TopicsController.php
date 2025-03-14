<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\Request;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    /**
     * TopicsController constructor.
     */
    public function __construct()
    {
        // Auth middleware
        // 只让未登录用户访问话题列表页和话题详情页, 其他页面需要登录
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Show topics list.
     *
     * @param Request $request
     * @param Topic $topic
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
        $categories = Category::all();
        return view('topics.create', compact('topic', 'categories'));
    }

    /**
     * Store topic.
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return RedirectResponse
     */
    public function store(TopicRequest $request, Topic $topic): RedirectResponse
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();
        return redirect()->route('topics.show', $topic->id)->with('success', 'Created successfully.');
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
        $categories = Category::all();
        return view('topics.edit', compact('topic', 'categories'));
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

        return redirect()->route('topics.show', $topic->id)->with('success', 'Updated successfully.');
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

        return redirect()->route('topics.index')->with('success', 'Deleted successfully.');
    }

    /**
     * Topic upload image.
     *
     * @param Request $request
     * @param ImageUploadHandler $handler
     * @return array
     */
    public function uploadImage(Request $request, ImageUploadHandler $handler): array
    {
        $data = [
            'success' => false,
            'msg' => 'Upload failed!',
            'file_path' => ''
        ];

        if ($file = $request->upload_file) {
            $result = $handler->save($file, 'topics', Auth::id(), 1024);
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg'] = 'Upload succeeded!';
                $data['success'] = true;
            }
        }

        return $data;
    }
}
