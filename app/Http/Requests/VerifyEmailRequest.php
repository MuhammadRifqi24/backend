<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifyEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uuid' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ];
    }

    public function messages(): array
    {
        return [
            'uuid.required' => 'Kode UUID Harus di Isi',
            'email.required' => 'Email Harus di Isi',
            'email.email' => 'Format Email Salah',
            'password.required' => 'Password Harus di Isi',
            'password_confirmation.required' => 'Re-Password Harus di Isi',
            'password_confirmation.same' => 'Re-Password tidak sama dengan Password'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }
}
