<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnneeScolaireRequest extends FormRequest
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
            'libelle'=>'required|unique:annee_scolaires,libelle'
        ];
    }

    public function messages()
    {
        return [
            'libelle.required' => 'Le libelle de l\'année scolaire est obligatoire',
            'libelle.unique' => 'Le libelle de l\'année scolaire existe déjà',
        ];
    }
}
