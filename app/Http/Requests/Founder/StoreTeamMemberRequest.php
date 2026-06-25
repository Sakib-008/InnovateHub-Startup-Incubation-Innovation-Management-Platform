<?php

namespace App\Http\Requests\Founder;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isFounder();
    }

    public function rules(): array
    {
        return [
            'email'        => ['required', 'email', 'exists:users,email'],
            'role_in_team' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'No user found with that email address.',
        ];
    }
}