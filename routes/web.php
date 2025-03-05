<?php

use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mews\Captcha\Captcha;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PagesController@root')->name('root');

Auth::routes(['verify' => true]);

// 手动注册邮箱验证相关路由
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed'])->name('verification.verify');

    Route::post('/email/resend', [VerificationController::class, 'resend'])
        ->name('verification.resend');
});

// 用户身份验证相关的路由
// GET|HEAD   login ...................................... login › Auth\LoginController@showLoginForm
// POST       login ...................................................... Auth\LoginController@login
// POST       logout ........................................... logout › Auth\LoginController@logout

// 用户注册相关的路由
// GET|HEAD   register ...................... register › Auth\RegisterController@showRegistrationForm
// POST       register ............................................. Auth\RegisterController@register

// 密码重置相关的路由
// GET|HEAD   password/reset ... password.request › Auth\ForgotPasswordController@showLinkRequestForm
// POST       password/reset ................... password.update › Auth\ResetPasswordController@reset
// GET|HEAD   password/reset/{token} .... password.reset › Auth\ResetPasswordController@showResetForm

// 再次确认密码相关的路由
// GET|HEAD   password/confirm .... password.confirm › Auth\ConfirmPasswordController@showConfirmForm
// POST       password/confirm ............................... Auth\ConfirmPasswordController@confirm

// 邮箱验证相关的路由
// POST       password/email ...... password.email › Auth\ForgotPasswordController@sendResetLinkEmail

Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);
// GET|HEAD        users/{user} ............. users.show › UsersController@show
// GET|HEAD        users/{user}/edit ........ users.edit › UsersController@edit
// PUT|PATCH       users/{user} ......... users.update › UsersController@update

Route::resource('topics', 'TopicsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
// GET|HEAD        topics ....................................... topics.index › TopicsController@index
// POST            topics ....................................... topics.store › TopicsController@store
// GET|HEAD        topics/create .............................. topics.create › TopicsController@create
// GET|HEAD        topics/{topic} ................................. topics.show › TopicsController@show
// PUT|PATCH       topics/{topic} ............................. topics.update › TopicsController@update
// DELETE          topics/{topic} ........................... topics.destroy › TopicsController@destroy
// GET|HEAD        topics/{topic}/edit ............................ topics.edit › TopicsController@edit
// GET|HEAD        users/{user} ..................................... users.show › UsersController@show
// PUT|PATCH       users/{user} ................................. users.update › UsersController@update
// GET|HEAD        users/{user}/edit ................................ users.edit › UsersController@edit

Route::resource('categories', 'CategoriesController', ['only' => ['show']]);
// GET|HEAD        categories/{category} .................. categories.show › CategoriesController@show

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('topics', 'TopicsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);

Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

Route::resource('replies', 'RepliesController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);

Route::resource('replies', 'RepliesController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
