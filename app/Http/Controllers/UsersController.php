<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        // Only authenticated guests can access the users.show page
        $this->middleware('auth', ['except' => ['show']]);
    }


    /**
     * Display the user's profile page.
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function show(User $user): Factory|View|Application
    {
        return view('users.show', compact('user'));
    }

    /**
     * Display the user's edit form.
     *
     * @param User $user
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(User $user): Factory|View|Application
    {

        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     *
     * @param UserRequest $request
     * @param User $user
     * @param ImageUploadHandler $uploader
     * @return RedirectResponse
     */
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user): RedirectResponse
    {
        $data = $request->all();
        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id, 416);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', 'Profile updated successfully.');
    }

    /**
     * 模拟登录
     *
     * @param $id
     * @param Request $request
     * @return Redirector|Application|RedirectResponse
     */
    public function impersonateUser($id, Request $request): Redirector|Application|RedirectResponse
    {
        $user = User::find($id);

        if ($user) {
            Auth::user()->impersonate($user);
            // 获取传递过来的重定向 URL，如果没有则默认重定向到首页
            $redirectTo = $request->input('redirect_to', '/');
            return redirect($redirectTo);
        }

        return redirect()->back()->with('error', 'User not found.');
    }

    /**
     * 停止模拟登录
     *
     * @param Request $request
     * @return Redirector|Application|RedirectResponse
     */
    public function stopImpersonating(Request $request): Redirector|Application|RedirectResponse
    {
        Auth::user()->leaveImpersonation();
        // 获取传递过来的重定向 URL，如果没有则默认重定向到首页
        $redirectTo = $request->input('redirect_to', '/');
        return redirect($redirectTo);
    }
}
