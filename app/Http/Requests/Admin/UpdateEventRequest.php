<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string', 'min:20'],
            'location'      => ['required', 'string', 'max:255'],
            'event_date'    => ['required', 'date'],
            'status'        => ['required', 'in:upcoming,ongoing,completed,cancelled'],
            'max_attendees' => ['nullable', 'integer', 'min:1'],
            'banner_image'  => ['nullable', 'image', 'max:2048'],
        ];
    }
}