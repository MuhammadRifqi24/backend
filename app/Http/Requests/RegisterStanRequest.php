<?php

namespace App\Http\Requests;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterStanRequest extends FormRequest
{
    use ApiResponser;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'name_stan' => 'required',
            'email' => 'required|email',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Lengkap Harus di Isi',
            'name_stan.required' => 'Nama Stan Harus di Isi',
            'email.required' => 'Email Harus di Isi',
            'email.email' => 'Format Email Salah'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }
}
