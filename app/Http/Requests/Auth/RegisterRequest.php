<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'min:2', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                'required_with:password_confirmation',
                'same:password_confirmation',
                $passwordRule,
            ],
            'password_confirmation' => [
                'required',
                'same:password',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
