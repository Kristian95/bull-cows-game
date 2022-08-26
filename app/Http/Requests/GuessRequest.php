<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'number' => ['required', 'numeric', 'digits:4']
        ];
    }
}
