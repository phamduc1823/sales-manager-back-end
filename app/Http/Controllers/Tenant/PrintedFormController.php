<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\PrintedFormRequest;
use App\Models\Tenant\PrintedForm;
use Illuminate\Http\Request;

class PrintedFormController extends Controller
{

    public function __construct(
        private PrintedForm        $model,
        private PrintedFormRequest $request
    )
    {
    }

    //
    public function list()
    {
        try {
            return responseApi($this->model::query()->paginate(10), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function store()
    {
        try {
            $this->model::create($this->request->all());
            return responseApi("Tạo thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show()
    {
        try {
            return responseApi($this->model::find($this->request->id), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update()
    {
        try {
            $this->model::find($this->request->id)->update($this->request->all());
            return responseApi("Cập nhật thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete()
    {
        try {
            $this->model::find($this->request->id)->delete();
            return responseApi("Xóa thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function return()
    {
        $this->model::query()->find($this->request->id)->update(config('printed_form'));
    }
}
