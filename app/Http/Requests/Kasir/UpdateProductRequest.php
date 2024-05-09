<?php

namespace App\Http\Requests\Kasir;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|boolean',
            'name' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:10240', // Max:10MB
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'uuid.required' => 'UUID Harus ada',
            'category_id.required' => 'Kategori Harus di Isi',
            'category_id.exists' => 'Kategori tidak terdeteksi',
            'name.required' => 'Nama Lengkap Harus di Isi',
            'image.mimes' => 'Format Gambar tidak sesuai',
            'harga_beli.numeric' => 'Harga Beli harus berupa Angka',
            'harga_jual.numeric' => 'Harga Beli harus berupa Angka',
            'status.required' => 'Status Product harus di Isi',
            'status.boolean' => 'Status Product harus boolean (true / false)',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }
}
