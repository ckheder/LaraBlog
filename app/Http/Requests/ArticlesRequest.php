<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ArticlesRequest extends FormRequest
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
                'corps_article' => 'required',
                'titre_article' => ['required',Rule::unique('articles')->ignore($this->idarticle, 'id_article')]
                ];
    }

    public function messages()
    {
        return [
                'titre_article.unique' => 'Ce titre d\'article existe déjà',
                'titre_article.required' => 'Vous devez entrer un titre pour cet article.',
                'corps_article.required' => 'Vous devez compléter cet article.',
        ];
    }
}
