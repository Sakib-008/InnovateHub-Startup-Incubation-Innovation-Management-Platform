<?php

namespace App\Http\Requests\Founder;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowcaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isFounder();
    }

    public function rules(): array
    {
        return [
            'tagline'        => ['nullable', 'string', 'max:255'],
            'achievements'   => ['nullable', 'string', 'max:2000'],
            'website'        => ['nullable', 'url', 'max:255'],
            'is_public'      => ['boolean'],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => ['image', 'max:2048'],
        ];
    }
}