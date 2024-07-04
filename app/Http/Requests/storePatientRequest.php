<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storePatientRequest extends FormRequest
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
            'fullName' =>['required', 'string', 'min:2', 'max:255'],
            'phone' =>['required', 'numeric'],
            'address' =>['required', 'string', 'min:3', 'max:255'],
            'age' =>['required', 'numeric'],
            'gender' =>['required', 'in:male,female'],
            'email' =>['required', 'email','unique:patients'],
            'password' => ['required', 'min:4', 'max:255']

        ];
    }
}
