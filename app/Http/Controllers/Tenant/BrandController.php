<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\BrandRequest;
use App\Models\Tenant\Brand;

class BrandController extends Controller
{
    //
    public function __construct(
        private Brand $model,
        private BrandRequest $request
    )
    {
    }
    public function list(){
        try {
            return responseApi($this->model::query()
                ->orderBy('id','desc')
                ->get(), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
    public function store(){
        try {
            if (!empty($this->request->validated())) {
                $this->model::create($this->request->all());
                return responseApi("Tạo thành công!", true);
            }
            return responseApi("Tạo thất bại!", false);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
    public function show()
    {
        try {
            return responseApi($this->model::find($this->request->id), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
    public function update()
    {
        try {
            if (!empty($this->request->validated())) {
                $category = $this->model::find($this->request->id);
                $category->update($this->request->all());
                return responseApi("Cập nhật thành công!", true);
            }
            return responseApi("Cập nhật thất bại!", false);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
    public function delete(){
        try {
            $this->model::find($this->request->id)->delete();
            return responseApi("Xóa thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
