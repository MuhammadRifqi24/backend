<?php

namespace App\Http\Requests\Pelayan;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTableInfoRequest extends FormRequest
{
    use ApiResponser;

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
            'uuid' => 'required',
            'user_id' => 'nullable|exists:users,id',
            'name' => 'nullable',
            'status' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'uuid.required' => 'UUID Harus ada',
            'user_id.exists' => 'User tidak terdeteksi',
            'status.boolean' => 'Status Table Info harus boolean (true / false)',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }
}