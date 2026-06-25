<?php

namespace App\Http\Requests\Founder;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIdeaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only the idea owner can update it
        return $this->route('idea')->founder_id === auth()->id();
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
}