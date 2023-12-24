<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seed;
use App\Http\Requests\SeedRequest;
class SeedController extends Controller
{
    public function __construct(
        private Seed $model,
        private string $module_name = "Seed",
    )
    {
    }
    public function index()
    {
        $seeds = $this->model::orderBy('created_at','desc')->get();
        return view('admin.seed.index', compact('seeds'));
    }
    public function store(SeedRequest $request,){
        try {
            if (!empty($request->validated())) {
                $this->model::create($request->all());
                return responseApi("Tạo thành công!", true);
            }
            return responseApi("Tạo thất bại!", false);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
    public function show(Request $request)
    {
        try {
            if (!$this->model::find($request->id)) return responseApi($this->module_name." không tồn tại!", false);
            return responseApi($this->model::find($request->id), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
    public function update(SeedRequest $request){
        try {
            if (!$this->model::find($request->id)) return responseApi($this->module_name." không tồn tại!", false);
            if (!empty($request->validated())) {
                $category = $this->model::find($request->id);
                $category->update($request->all());
                return responseApi("Cập nhật thành công!", true);
            }
            return responseApi("Cập nhật thất bại!", false);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
    public function delete(Request $request){
        try {
            if (!$this->model::find($request->id)) return responseApi($this->module_name." không tồn tại!", false);
            $this->model::find($request->id)->delete();
            return responseApi("Xóa thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
