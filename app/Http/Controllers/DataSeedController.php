<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessFieldSeed;
use App\Models\Seed;
use App\Models\BusinessField;
use App\Http\Requests\DataSeedRequest;
class DataSeedController extends Controller
{
    public function __construct(
        private BusinessFieldSeed $model,
        private string            $module_name = "DataSeed",
    )
    {
    }

    public function index()
    {
        $dataBusinessFields = BusinessField::get(['id', 'name', 'code']);
        $seeds = Seed::get(['id', 'name', 'type']);
        $data_seeds = $this->model::with('businessField', 'seed')->orderBy('created_at', 'desc')->get();
        $dataSeeds = $data_seeds->map(function ($item) {
            return [
                'id' => $item->id,
                'business_field_id' => $item->business_field_id,
                'business_field_name' => $item->businessField->name,
                'business_field_code' => $item->businessField->code,
                'seed_id' => $item->seed_id,
                'seed_name' => $item->seed->name,
                'seed_type' => $item->seed->type,
            ];
        });
        return view('admin.data-seed.index', compact('dataSeeds', 'dataBusinessFields', 'seeds'));
    }

    public function store(DataSeedRequest $request)
    {
        try {
            $this->model->create($request->all());
            return redirect()->route('admin.data-seed.index')->with('success', 'Thêm mới thành công');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-seed.index')->with('error', 'Thêm mới thất bại');
        }
    }

    public function show(Request $request)
    {
        try {
            $data = $this->model::with('businessField:id,name', 'seed:id,name')->where('id',$request->id)->get();
            $dataSeeds = $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'business_field_id' => $item->business_field_id,
                    'business_field_name' => $item->businessField->name,
                    'seed_id' => $item->seed_id,
                    'seed_name' => $item->seed->name,
                ];
            });
            return responseApi(collect($dataSeeds)->collapse(), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), 'error');
        }
    }

    public function update(DataSeedRequest $request)
    {
        try {
            $data = $this->model->find($request->id);
            if (!$data) return responseApi($this->module_name . " không tồn tại!", false);
            $data->update($request->all());
            return responseApi("Cập nhật thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), 'error');
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = $this->model->find($request->id);
            if (!$data) return responseApi($this->module_name . " không tồn tại!", false);
            $data->delete();
            return responseApi("Xóa thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), 'error');
        }
    }
}
