<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

function createFolder($folderName, $cloud = 'google')
{
    $googleDisk = Storage::disk($cloud)->makeDirectory($folderName);
    return $googleDisk;
}

function createDoc($folderName, $fileName, $content, $cloud = 'google')
{
    $googleDisk = Storage::disk($cloud)->put($folderName . '/' . $fileName, $content);
    if ($googleDisk) {
        $id = Storage::disk($cloud)->getAdapter()
            ->getMetadata($folderName . '/' . $fileName)
            ->extraMetadata()['id'];
    }
    return $id;
}

function createFile($folderName, $file, $fileName, $cloud = 'google')
{
    $googleDisk = Storage::disk($cloud)->putFileAs($folderName, $file, $fileName);
    if ($googleDisk) {
        $id = Storage::disk($cloud)->getAdapter()
            ->getMetadata($folderName . '/' . $fileName)
            ->extraMetadata()['id'];
    }
    return $id;
}

function deleteFile($folderName, $fileName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName . '/' . $fileName) == false) {
        return "File not found";
    }
    $googleDisk = Storage::disk($cloud)->delete($folderName . '/' . $fileName);
    return $googleDisk;
}

function deleteFolder($folderName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName) == false) {
        return "Folder not found";
    }
    $googleDisk = Storage::disk($cloud)->deleteDirectory($folderName);
    return $googleDisk;
}

function renameFile($folderName, $oldName, $newName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName . '/' . $oldName) == false) {
        return "File not found";
    }
    $googleDisk = Storage::disk($cloud)->move($folderName . '/' . $oldName, $folderName . '/' . $newName);
    return $googleDisk;
}

function renameFolder($oldName, $newName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($oldName) == false) {
        return "Folder not found";
    }
    $googleDisk = Storage::disk($cloud)->move($oldName, $newName);
    return $googleDisk;
}

function listFile($folderName = '/', $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName) == false) {
        return "Folder not found";
    }
    $googleDisk = collect(Storage::disk($cloud)->listContents($folderName, true));
    return $googleDisk;
}

function getLinkImage($folderName, $fileName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName . '/' . $fileName)) {
        $id = Storage::disk($cloud)->getAdapter()
            ->getMetadata($folderName . '/' . $fileName)
            ->extraMetadata()['id'];
        $url = 'https://lh3.google.com/d/' . $id;
        return $url;
    }
    return "File not found";
}

function getContent($folderName, $fileName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName . '/' . $fileName) == false) {
        return "File not found";
    }
    $content = Storage::disk($cloud)->get($folderName . '/' . $fileName);
    return $content;
}

if (!function_exists('responseApi')) {
    function responseApi(mixed $data = "Not found", bool $status = false, int $code = 200, mixed $dataAppend = []): \Illuminate\Http\JsonResponse
    {

        if (!$status) $data = ['status' => $status, 'meta' => $data];

        if ($status) $data = ['status' => $status, 'payload' => $data];

        if ($status) $data = array_merge($data, $dataAppend);

        return response()->json(
            $data,
            $code,
        );
    }
}

function paginateCustom($data, $dataPaginate)
{
    return new \Illuminate\Pagination\LengthAwarePaginator(
        $data,
        $dataPaginate->total(),
        $dataPaginate->perPage(),
        $dataPaginate->currentPage(), [
            'path' => \Request::url(),
            'query' => [
                'page' => $dataPaginate->currentPage()
            ]
        ]
    );
}

function generateUserToken($user): array
{
    $token = $user->createToken('authToken');
    $expired_at = Carbon::now()->addHour('12');
    $token->expired_at = $expired_at;
    $tokenGen = $token->plainTextToken;
    $data = [
        'user' => $user,
        'token' => $tokenGen,
        'expired_at' => $expired_at,
        'token_type' => 'Bearer',
    ];
    return $data;
}

function getTenantMenus($role = 'super-admin'): array
{
    return collect(config('util.TENANT_MENUS'))
        ->filter(function ($menu) use ($role) {
            return in_array($role, $menu['roles']);
        })
        ->pluck('name')
        ->toArray();
}

function removeVietnameseAccents($str)
{
    $unicode = [
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'D' => 'Đ',
        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    ];
    foreach ($unicode as $nonDiacritic => $diacritics) {
        $str = preg_replace("/($diacritics)/i", $nonDiacritic, $str);
    }
    return $str;
}

function cleanText($str)
{
    $str = strtolower($str);
    $str = removeVietnameseAccents($str);
    $str = str_replace(' ', '_', $str);
    return $str;
}
