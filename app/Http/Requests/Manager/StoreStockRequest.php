<?php

namespace App\Http\Requests\Manager;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreStockRequest extends FormRequest
{
    use ApiResponser;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'qty' => 'required|numeric',
            'description' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'qty.required' => 'Qty Harus di Isi',
            'qty.numeric' => 'Qty Harus angka'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }
}
