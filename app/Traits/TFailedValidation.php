<?php

namespace App\Traits;

trait TFailedValidation
{
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $meta = ['errors' => $validator->errors(),];
        $response = responseApi($meta, false);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
