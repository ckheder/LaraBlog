<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
                'name' => 'required|unique:users',
                'email' => 'required|confirmed|email|unique:users',
                'password' => 'required|confirmed|min:8'
                ];
    }

    public function messages()
    {
        return [
                'name.required' => 'Vous devez entrer un nom d\'utilisateur.',
                'name.unique' => 'Ce nom existe déjà',
                'email.confirmed' => 'Les deux adresses mail ne correspondent pas',
                'email.required' => 'Une adresse mail valide est requise.',
                'email.unique' => 'Cette adresse mail existe déjà.',
                'password.required' => 'Vous devez compléter cet article.',
                'password.confirmed' => 'Les deux mots de passe ne correspondent pas',
                'password.min' => 'Le mot de passe doit faire au minimum 8 caractères.'
        ];
    }
}
