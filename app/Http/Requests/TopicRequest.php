<?php

namespace App\Http\Requests;

class TopicRequest extends Request
{
    public function rules(): array
    {
        switch ($this->method()) {
            // CREATE
            case 'POST':

            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title' => 'required|min:2',
                    'body' => 'required|min:3',
                    'category_id' => 'required|numeric',
                ];
            }
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            }
        }
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.min' => 'Title must be at least 2 characters.',
            // 'title.min' => 'タイトルは2文字以上で入力してください。',
        ];
    }
}
