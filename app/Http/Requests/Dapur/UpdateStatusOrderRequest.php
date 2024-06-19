<?php

namespace App\Http\Requests\Dapur;

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
            'status' => 'required|in:2,3,5',
        ];
    }

    public function messages(): array
    {
        return [
            'uuid.required' => 'UUID Harus ada',
            'status.required' => 'Status Order Harus ada',
            'status.in' => 'Status Order harus salah satu dari: 2, 3, 5',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }
}
