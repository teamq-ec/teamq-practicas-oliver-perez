<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
    return [
        'name'      => 'required|string|max:255',
        'lastname'  => 'required|string|max:255',
        'birthdate' => 'required|date',
        'address'   => 'required|string',
        'country'   => 'required|string|max:255',
        'zip'       => 'required|numeric|digits_between:1,15',
        'telephone' => 'required|numeric|digits_between:1,15',
        'position'  => 'required|string|max:255',
        'department'=> 'required|string|max:255',
        'companyage'=> 'required|date',
        'state' => 'required|boolean',
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:8|max:255',
        'rol_id' => 'required|exists:roles,id'
    ];
    }
}
