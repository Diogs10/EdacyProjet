<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'nom'=>'required|unique:salles,nom',
            'effectif'=>'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'nom.required'=>'Le nom de la salle est obligatoire',
            'nom.unique'=>'Le nom de la salle est unique',
            'effectif.required'=>'Effectif est obligatoire',
            'effectif.numeric'=>'Effectif est un nombre',
        ];
    }
}
