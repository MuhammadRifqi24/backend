<?php

namespace App\Http\Requests;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class VerifyEmailOwnerRequest extends FormRequest
{
    use ApiResponser;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uuid' => 'required',
            'email' => 'required|email'
        ];
    }

    public function messages(): array
    {
        return [
            'uuid.required' => 'Kode UUID Harus di Isi',
            'email.required' => 'Email Harus di Isi',
            'email.email' => 'Format Email Salah'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator->errors(), 'Validation Error.', 422);
    }
}
