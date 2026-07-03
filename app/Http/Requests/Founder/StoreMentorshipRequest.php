<?php

namespace App\Http\Requests\Founder;

use Illuminate\Foundation\Http\FormRequest;

class StoreMentorshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isFounder();
    }

    public function rules(): array
    {
        return [
            'mentor_id' => ['required', 'exists:users,id'],
            'message'   => ['nullable', 'string', 'max:500'],
        ];
    }
}