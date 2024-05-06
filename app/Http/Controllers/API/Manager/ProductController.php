<?php

namespace App\Http\Controllers\API\Manager;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests;
use App\Services\FileUploadService as FUS;

class ProductController extends Controller
{
    protected $productService;
    protected $cafeService;
    public function __construct(Services\ProductService $productService, Services\CafeService $cafeService)
    {
        $this->productService = $productService;
        $this->cafeService = $cafeService;
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();
        $result = $this->productService->getDataByID($auth->id, 'cafe_id');
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }

        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function insert(Requests\Manager\StoreProductRequest $request): JsonResponse
    {
        $auth = $request->user();

        //* check cafe_management
        $cafe_management = $this->cafeService->getCafe($auth->id, 'get_info');
        if ($cafe_management['status'] == false) {
            return $this->errorResponse($cafe_management['result'], $cafe_management['message'], $cafe_management['code']);
        }
        $cafe_management = $cafe_management['result'];

        $name_image = null;
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $name_image = $cafe_management['cafe_id'] . '-product-' . $auth->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $upload = FUS::uploadProduct($file, $name_image, '');
            if ($upload['status'] === false) {
                return $this->errorResponse($upload['result'], "Gagal Upload", 500);
            }
        }

        $result = $this->productService->insertData([
            'cafe_id' => $cafe_management['cafe_id'],
            'stan_id' => $cafe_management['stan_id'],
            'category_id' => $request->category_id,
            'name' => $request->name,
            'image' => $name_image,
            'description' => $request->description,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'is_stock' => $request->is_stock,
            'qty' => $request->qty,
            'status' => true
        ]);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function update(Requests\Manager\UpdateProductRequest $request): JsonResponse
    {
        $checkData = $this->productService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['message'], $checkData['result'], $checkData['code']);
        }
        $checkData = $checkData['result'];

        $auth = $request->user();
        $name_image = $checkData->image;
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $name_image = $checkData->cafe_id . '-product-' . $auth->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $upload = FUS::uploadProduct($file, $name_image, $checkData->image);
            if ($upload['status'] === false) {
                return $this->errorResponse($upload['result'], "Gagal Upload", 500);
            }
        }

        $result = $this->productService->updateData([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'image' => $name_image,
            'description' => $request->description,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'status' => $request->status
        ], $checkData->id);

        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }

    public function destroy(Requests\Manager\DeleteProductRequest $request): JsonResponse
    {
        $checkData = $this->productService->getDataByID($request->uuid, 'uuid');
        if ($checkData['status'] == false) {
            return $this->errorResponse($checkData['result'], $checkData['message'], $checkData['code']);
        }

        $result = $this->productService->deleteData($request->uuid);
        if ($result['status'] == false) {
            return $this->errorResponse($result['result'], $result['message'], $result['code']);
        }
        return $this->successResponse($result['result'], $result['message'], $result['code']);
    }
}
