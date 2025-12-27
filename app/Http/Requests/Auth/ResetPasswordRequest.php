<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    /**
     * @return array<string, array<int, ValidationRule|string>|ValidationRule|string>
     */
    public function rules(): array
    {
        /** @var ValidationRule $passwordRule */
        $passwordRule = Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols();

        return [
            'password' => [
                'required',
                'confirmed',
                $passwordRule,
            ],
            'password_confirmation' => [
                'required',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
