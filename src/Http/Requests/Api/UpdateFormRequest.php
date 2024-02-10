<?php

namespace Sakydev\Boring\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Sakydev\Boring\Models\Form;
use Sakydev\Boring\Resources\Api\Responses\ErrorResponse;

class UpdateFormRequest extends FormRequest
{
    public function rules()
    {
        $userId = Auth::id();
        $existingSlug = $this->route('slug');

        return [
            'name' => [
                Rule::requiredIf(function () {
                    return !$this->input('slug');
                }),
                'string',
                'min:3',
                'max:50',
                'regex:/^[0-9a-z\s]+$/i',
                Rule::unique('forms', 'name')->where(function ($query) use ($userId, $existingSlug) {
                    return $query->where('user_id', $userId)->where('slug', '!=', $existingSlug);
                }),
            ],
            'slug' => [
                Rule::requiredIf(function () {
                    return !$this->input('name');
                }),
                'string',
                'min:3',
                'max:100',
                'regex:/^[0-9a-z_-]+$/i',
                Rule::unique('forms', 'slug')->where(function ($query) use ($userId, $existingSlug) {
                    return $query->where('user_id', $userId)->where('slug', '!=', $existingSlug);
                }),
            ],
        ];
    }
}
