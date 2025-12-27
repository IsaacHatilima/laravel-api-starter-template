<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TwoFactorCodeRequest extends FormRequest
{
    /**
     * @return array<string, array<int, ValidationRule|string>|ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
