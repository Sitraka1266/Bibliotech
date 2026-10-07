<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => 'Le champ « :attribute » est obligatoire.',
            'string' => 'Le champ « :attribute » doit être un texte.',
            'integer' => 'Le champ « :attribute » doit être un nombre entier.',
            'email' => 'Veuillez saisir une adresse email valide.',
            'date' => 'Le champ « :attribute » doit être une date valide.',
            'unique' => 'Cette valeur existe déjà pour « :attribute ».',
            'exists' => 'La valeur choisie pour « :attribute » est introuvable.',
            'max.string' => 'Le champ « :attribute » ne doit pas dépasser :max caractères.',
            'min.numeric' => 'Le champ « :attribute » doit être supérieur ou égal à :min.',
            'max.numeric' => 'Le champ « :attribute » doit être inférieur ou égal à :max.',
        ];
    }
}
