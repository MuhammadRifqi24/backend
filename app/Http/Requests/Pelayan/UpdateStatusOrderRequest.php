<?php

namespace App\Http\Requests\Pelayan;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateStatusOrderRequest extends FormRequest
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
            'status' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'uuid.required' => 'UUID Harus ada',
            'status.required' => 'Status Harus ada',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }
}
