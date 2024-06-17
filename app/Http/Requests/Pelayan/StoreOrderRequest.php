<?php

namespace App\Http\Requests\Pelayan;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
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
            'table_info_id' => 'nullable|exists:table_info,id',
            'cafe_id' => 'nullable|exists:cafes,id',
            'customer_name' => 'required',
            'note' => 'nullable',
            'total_price' => 'required|numeric',
            'order_type' => 'required',
            // 'order_details' => 'required|array',
            // 'order_details.*.stan_id' => 'nullable|exists:stans,id',
            // 'order_details.*.product_id' => 'required|exists:products,id',
            // 'order_details.*.qty' => 'required',
            // 'order_details.*.price' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'UserId tidak terdeteksi',
            'table_info_id.exists' => 'TableInfoId tidak terdeteksi',
            'cafe_id.exists' => 'CafeId tidak terdeteksi',
            'customer_name.required' => 'Nama Customer Harus di Isi',
            'total_price.required' => 'Total Price harus di Isi',
            'order_type.required' => 'Tipe Order harus di Isi, Takeaway atau Dine-in',
            // 'order_details.required' => 'Detail order harus diisi',
            // 'order_details.array' => 'Detail order harus berupa array',
            // 'order_details.*.stan_id.exists' => 'StanId pada detail order tidak terdeteksi',
            // 'order_details.*.product_id.required' => 'ProductId pada detail order harus diisi',
            // 'order_details.*.product_id.exists' => 'ProductId pada detail order tidak terdeteksi',
            // 'order_details.*.qty.required' => 'Kuantitas pada detail order harus diisi',
            // 'order_details.*.price.required' => 'Harga pada detail order harus diisi',
            // 'order_details.*.price.numeric' => 'Harga pada detail order harus berupa angka',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }
}
