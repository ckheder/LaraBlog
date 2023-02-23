<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TagsRequest extends FormRequest
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
                'nametags' => ['required',Rule::unique('tags')->ignore($this->idtag, 'id_tag')]
                ];
    }

    public function messages()
    {
        return [
                'nametags.unique' => 'Cette catégorie existe déjà.',
                'nametags.required' => 'Vous devez entrer un nom pour cette catégorie.',
        ];
    }
}
