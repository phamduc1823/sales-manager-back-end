<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Tenant\Config;
use App\Http\Requests\Tenant\ConfigRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    public function __construct(
        private Config        $model,
        private ConfigRequest $request
    )
    {
    }

    /**
     * @path /tenant/api/v1/config/store
     * @method POST
     * @param ConfigRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store()
    {
        DB::beginTransaction();
        try {
            if ($this->request->hasFile('logo')) {
                $dir = $this->request->business_name;
                $file = $this->request->file('logo');
                $fileName = $file->getClientOriginalName();
                $fileId = createFile($dir, $file, $fileName);
                $fileUrl = "https://lh3.google.com/d/" . $fileId;
                $this->request->merge(['logo' => $fileUrl]);
            }
            $this->model::create($this->request->all());
            DB::commit();
            return responseApi("Tạo thành công!", true);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/config/show
     * @method POST
     * @param ConfigRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function show()
    {
        try {
            $config = $this->model::first();
            return responseApi($config, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/config/update
     * @method POST
     * @param ConfigRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update()
    {
        DB::beginTransaction();
        try {
            if ($this->request->hasFile('logo')) {
                $dir = $this->request->business_name;
                $file = $this->request->file('logo');
                $fileName = $file->getClientOriginalName();
                $fileId = createFile($dir, $file, $fileName);
                $fileUrl = "https://lh3.google.com/d/" . $fileId;
                $this->request->merge(['logo' => $fileUrl]);
            } else {
                $config = $this->model::first();
                $this->request->merge(['logo' => $config->logo]);
            }
            $this->model::where('id', $this->request->id)->update($this->request->except(['id', 'domain_name', 'location_id', 'inventory_id', 'business_field_id']));
            $tenant = Tenant::current()->update($this->request->only(['domain_name', 'business_field_id']));
            DB::commit();
            return responseApi("Cập nhật thành công!", true);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi($throwable->getMessage(), false);
        }
    }
}
