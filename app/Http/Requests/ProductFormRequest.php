<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'matricola' => ['required', 'string', 'max:255'],
            'descrizione' => ['required', 'string', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'matricola.required' => 'La matricola è obbligatoria',
            'descrizione.required' => 'La descrizione è obbligatoria'
        ];
    }
}
