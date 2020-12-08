<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientFormRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:255'],
            'cognome' => ['required', 'string', 'max:255'],
            'cod_fisc' => ['required', 'string', 'max:255'],
            'indirizzo' => ['required', 'string', 'max:255'],
            'cap' => ['required', 'string', 'max:7'],
            'citta' => ['required', 'string', 'max:255'],
            'provincia' => ['required', 'string', 'max:4'],
            'telefono' => ['required', 'string', 'max:12'],
            'tipo' => ['required', 'string', 'max:12'],
            'fonte' => ['required', 'string', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'Il nome è obbligatorio',
            'cognome.required' => 'Il cognome è obbligatorio',
            'cod_fisc.required' => 'Il codice fiscale è obbligatorio',
            'indirizzo.required' => 'L\'indirizzo è obbligatorio',
            'cap.required' => 'il cap è obbligatorio',
            'provincia.required' => 'La provincia è obbligatoria',
            'telefono.required' => 'Il telefono è obbligatorio',
            'tipo.required' => 'Il tipo è obbligatorio',
            'fonte.required' => 'La fonte è obbligatoria'
        ];
    }
}
