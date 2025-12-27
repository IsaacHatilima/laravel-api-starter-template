<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * @return array<string, array<int, ValidationRule|string>|ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
