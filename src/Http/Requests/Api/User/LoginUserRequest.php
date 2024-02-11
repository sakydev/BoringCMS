<?php

namespace Sakydev\Boring\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'min:3',
                'max:50',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:100',
            ]
        ];
    }
}
