<?php

namespace App\Http\Requests\User;

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
            'table_info_id' => 'nullable|exists:table_info,id',
            'cafe_id' => 'required|exists:cafes,id',
            'customer_name' => 'required',
            'note' => 'nullable',
            'total_price' => 'required|numeric',
            'order_type' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'table_info_id.exists' => 'TableInfoId tidak terdeteksi',
            'cafe_id.exists' => 'CafeId tidak terdeteksi',
            'customer_name.required' => 'Nama Customer Harus di Isi',
            'total_price.required' => 'Total Price harus di Isi',
            'order_type.required' => 'Tipe Order harus di Isi, Takeaway atau Dine-in',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation Error.', 422)
        );
    }
}
