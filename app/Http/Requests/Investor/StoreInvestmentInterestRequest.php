<?php

namespace App\Http\Requests\Investor;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvestmentInterestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isInvestor();
    }

    public function rules(): array
    {
        return [
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }
}