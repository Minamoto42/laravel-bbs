<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * regex:/^[A-Za-z0-9\-\_]+$/ 这是一个正则表达式，用于限制用户名只包含 A-Z、a-z、0-9、- 和 _
     * dimensions:min_width=208,min_height=208 限制上传的图片文件宽和高的最小值, 单位是像素
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar' => 'mimes:jpeg,jpg,png,gif|dimensions:min_width=208,min_height=208',
        ];
    }

    /**
     * Custom the error message.
     * 这里只是一个简单的示例，我们在将来的工作开发中如果有类似的需求的话, 可以按照这个格式来写.
     * 也可以在 resources/lang/ja/validation.php 文件中添加自定义的错误提示信息。ja 是日语的意思，你可以根据需要替换成自己的语言代码。
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'The username has already been taken, please choose another one.',
            'name.regex' => 'The username can only contain letters, numbers, dashes and underscores.',
            'name.between' => 'The username must be between 3 and 25 characters.',
            'name.required' => 'The username is required.',
            'avatar.mimes' => 'The avatar must be a file of type: jpeg, jpg, png, gif.',
            'avatar.dimensions' => 'The avatar must be at least 208px in width and 208px in height.',
        ];
    }
}
