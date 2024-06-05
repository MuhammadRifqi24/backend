<?php

namespace App\Http\Requests\Owner;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRawMaterialRequest extends FormRequest
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
            'raw_material_category_id' => 'required|exists:raw_material_categories,id',
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
            'raw_material_category_id.required' => 'Kategori Bahan Baku Harus di Isi',
            'raw_material_category_id.exists' => 'Kategori Bahan Baku tidak terdeteksi',
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
