<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class RegisterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nom complet',
            'email' => 'Email',
            'password' => 'Mot de passe',
        ];
    }
}
