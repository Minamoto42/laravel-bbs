<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules(): array
    {
        return [
            'message' => 'required|min:2',
            // exists:replies,id 检测父级 ID 是否存在于 replies 表中
            // numeric 确保父级 ID 是一个数字类型, 数字或者是数字字符串
             'parent_id' => 'exists:replies,id|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            // Validation messages
        ];
    }
}