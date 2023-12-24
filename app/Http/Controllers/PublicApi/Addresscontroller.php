<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address\Province;
use App\Models\Address\District;
use App\Models\Address\Commune;

class Addresscontroller extends Controller
{
    public function getProvinces()
    {
        $provinces = Province::all();
        if (!$provinces) return response()->json(['status' => 'error', 'message' => 'No provinces found']);
        return response()->json([
            'status' => 'success',
            'results' => $provinces,
        ]);
    }

    public function getDistricts($province_id)
    {
        $districts = District::with('province:id,name')->where('province_id', $province_id)->get();

        if ($districts->isEmpty()) return response()->json(['status' => 'error', 'message' => 'No districts found']);

        $districts = $districts->map(function ($district) {
            return [
                'id' => $district->id,
                'name' => $district->name,
                'province' => $district->province->name
            ];
        });

        return response()->json([
            'status' => 'success',
            'results' => $districts,
        ]);
    }

    public function getCommunes($district_id)
    {
        $communes = Commune::with(['district.province:id,name'])->where('district_id', $district_id)->get(['id', 'name', 'district_id']);

        if ($communes->isEmpty()) return response()->json(['status' => 'error', 'message' => 'No districts found']);

        $communes = $communes->map(function ($communes) {
            return [
                'id' => $communes->id,
                'name' => $communes->name,
                'district' => $communes->district->name,
                'province' => $communes->district->province->name
            ];
        });
        return response()->json([
            'status' => 'success',
            'results' => $communes,
        ]);
    }
}
