<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::HOME;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Redeclare the method redirect to view by the session message after the password reset.
     * Redeclare ResetsPasswords trait method sendResetResponse.
     *
     * @param Request $request
     * @param $response
     * @return Redirector|Application|RedirectResponse
     */
    protected function sendResetResponse(Request $request, $response): Redirector|Application|RedirectResponse
    {
        session()->flash('success', 'Password reset successful.');
        return redirect($this->redirectPath());
    }
}
