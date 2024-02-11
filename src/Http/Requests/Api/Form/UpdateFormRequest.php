<?php

namespace Sakydev\Boring\Http\Requests\Api\Form;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
                Rule::unique('forms', 'name')->where(function ($query) use ($existingSlug) {
                    return $query->where('slug', '!=', $existingSlug);
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
                Rule::unique('forms', 'slug')->where(function ($query) use ($existingSlug) {
                    return $query->where('slug', '!=', $existingSlug);
                }),
            ],
        ];
    }
}
