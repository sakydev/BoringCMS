<?php

namespace Sakydev\Boring\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'string',
                'email,exists:boring_users,email',
            ],
            'password' => [
                'required',
                'string',
            ]
        ];
    }
}
