<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules(): array
    {
        return [
            'message' => 'required|min:2',
        ];
    }

    public function messages(): array
    {
        return [
            // Validation messages
        ];
    }
}
