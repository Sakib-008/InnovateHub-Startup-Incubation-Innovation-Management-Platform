<?php

namespace App\Http\Requests\Founder;

use Illuminate\Foundation\Http\FormRequest;

class StoreIdeaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isFounder();
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'category'    => ['required', 'string', 'max:100'],
            'pitch_file'  => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.min'        => 'Title must be at least 5 characters.',
            'description.min'  => 'Description must be at least 20 characters.',
            'pitch_file.mimes' => 'Pitch file must be a PDF, Word, or PowerPoint document.',
            'pitch_file.max'   => 'Pitch file must not exceed 5MB.',
        ];
    }
}