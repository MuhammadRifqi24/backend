<?php

namespace App\Http\Controllers\API\Owner;

use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests\Owner\StoreProductRequest;
// use App\Http\Controllers\Controller;
use App\Services\CafeService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $productService;
    protected $cafeService;
    public function __construct(ProductService $productService, CafeService $cafeService)
    {
        $this->productService = $productService;
        $this->cafeService = $cafeService;
    }

    public function store(StoreProductRequest $request)
    {

        $auth = $request->user();
        $cafe_management = $this->cafeService->getCafe($auth->id, 'get_info');
        if ($cafe_management['status'] == false) {
            return $this->errorResponse($cafe_management['result'], $cafe_management['message'], $cafe_management['code']);
        }

        return $this->successResponse($cafe_management['result'], $cafe_management['message'], $cafe_management['code']);

        // opsi -->

        // $validator = Validator::make($request->all(), [
        //     'category_id' => 'required|exists:categories,id',
        //     'name' => 'required',
        //     'image' => 'nullable|mimes:png,jpg,jpeg|max:10240', // Max:10MB
        //     'harga_beli' => 'required|numeric',
        //     'harga_jual' => 'required|numeric',
        //     'is_stock' => 'required|boolean',
        //     'qty' => 'required_if:is_stock,true'
        // ], [
        //     'category_id.required' => 'Kategori Harus di Isi',
        //     'category_id.exists' => 'Kategori tidak terdeteksi',
        //     'name.required' => 'Nama Harus di Isi',
        //     'image.mimes' => 'Format Gambar tidak sesuai',
        //     'harga_beli.required' => 'Harga Beli harus di Isi',
        //     'harga_beli.numeric' => 'Harga Beli harus berupa Angka',
        //     'harga_jual.required' => 'Harga Jual harus di Isi',
        //     'harga_jual.numeric' => 'Harga Jual harus berupa Angka',
        //     'is_stock.required' => 'Penentuan Stok harus di isi',
        //     'is_stock.boolean' => 'Penentuan menggunakan Stok harus boolean (true / false)',
        //     'qty.required_if' => 'Qty Stok harus diisi jika is_stock = true'
        // ]);

        // if ($validator->fails()) {
        //     return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        // }
    }
}
