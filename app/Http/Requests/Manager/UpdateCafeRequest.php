<?php

namespace App\Http\Requests\Manager;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCafeRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required',
            'logo' => 'nullable|mimes:png,jpg,jpeg|max:10240', // Max:10MB
            'address' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'uuid.required' => 'UUID Harus ada',
            'name.required' => 'Nama Kafe Harus di Isi',
            'description.required' => 'Description Harus di Isi',
            'logo.mimes' => 'Format Gambar tidak sesuai',
            'address.required' => 'Address Harus di Isi',
            'lat.required' => 'Lat Harus di Isi',
            'long.required' => 'Long Harus di Isi',
            'status.required' => 'Status Cafe harus di Isi',
            'status.boolean' => 'Status Cafe harus boolean (true / false)',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }   
}
