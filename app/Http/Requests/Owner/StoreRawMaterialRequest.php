<?php

namespace App\Http\Requests\Owner;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRawMaterialRequest extends FormRequest
{
    use ApiResponser;
    
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'raw_material_category_id' => 'required|exists:raw_material_categories,id',
            'name' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:10240', // Max:10MB
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'is_stock' => 'required|boolean',
            'qty' => 'required_if:is_stock,true'
        ];
    }

    public function messages(): array
    {
        return [
            'raw_material_category_id.required' => 'Kategori Bahan Baku Harus di Isi',
            'raw_material_category_id.exists' => 'Kategori Bahan Baku tidak terdeteksi',
            'name.required' => 'Nama Harus di Isi',
            'image.mimes' => 'Format Gambar tidak sesuai',
            'harga_beli.required' => 'Harga Beli harus di Isi',
            'harga_beli.numeric' => 'Harga Beli harus berupa Angka',
            'harga_jual.required' => 'Harga Jual harus di Isi',
            'harga_jual.numeric' => 'Harga Jual harus berupa Angka',
            'is_stock.required' => 'Penentuan Stok harus di isi',
            'is_stock.boolean' => 'Penentuan menggunakan Stok harus boolean (true / false)',
            'qty.required_if' => 'Qty Stok harus diisi jika is_stock = true'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }
}
