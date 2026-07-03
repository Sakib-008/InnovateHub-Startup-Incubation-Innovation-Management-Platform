<?php

namespace App\Http\Requests\Founder;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isFounder();
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'assigned_to' => ['required', 'exists:users,id'],
            'due_date'    => ['nullable', 'date'],
        ];
    }
}