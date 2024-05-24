<?php

namespace App\Http\Requests\User;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCallWaiterRequest extends FormRequest
{
    use ApiResponser;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'table_info_id' => 'required|exists:table_info,id',
            'cafe_id' => 'required|exists:cafes,id',
            'note' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'UserId tidak terdeteksi',
            'table_info_id.required' => 'TableInfoId Harus ada',
            'table_info_id.exists' => 'TableInfoId tidak terdeteksi',
            'cafe_id.required' => 'CafeId Harus ada',
            'cafe_id.exists' => 'CafeId tidak terdeteksi',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }
}
