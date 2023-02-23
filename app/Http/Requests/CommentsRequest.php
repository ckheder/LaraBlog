<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CommentsRequest extends FormRequest
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
            'comment'=> 'required'
        ];
    }


    public function messages()
    {
        return [
                
                'comment.required' => 'Vous devez compléter votre commentaire.'
        ];
    }

    protected function failedValidation(Validator $validator) 
    { 
        throw new HttpResponseException(
          response()->json([
            
            'error' => $validator->errors()->all()
          ], 200)
        ); 
      }
}
