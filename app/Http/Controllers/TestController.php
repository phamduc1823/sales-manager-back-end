<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MailRegister;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function test()
    {
        $url = "https://lh3.google.com/d/1UjCGJfruXVBHK8bFAnaCSXvFWejI0M2u";
        return view('test', compact('url'));
    }

    public function index()
    {
        $name = 'John Doe';
        $phone = '081234567890';
        $email = 'binhvt12003@gmail.com';
        Mail::to($email)->send(new MailRegister($email, $name, $phone, rand(100000, 999999)));
        dd('Mail Send Successfully');
    }

    public function create()
    {
        $fileDoc = createDoc('test3', 'test3.txt', 'Hello World');
        dd($fileDoc);
    }

    public function list()
    {
        $googleDisk = listFile('test3');
        return $googleDisk;
    }

    public function upload(Request $request)
    {
        $dir = 'test4';
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $googleDisk = createFile($dir, $file, $filename);
//        $googleDisk=Storage::disk('google')->putFileAs($dir,$file,$filename);
        dd($googleDisk);
    }

    public function rename()
    {

        $googleDisk = renameFolder('test3', 'test4');
        dd($googleDisk);
    }

    public function delete()
    {
        $filename = 'test3';
        $googleDisk = deleteFolder($filename);
        dd($googleDisk);
    }
}
