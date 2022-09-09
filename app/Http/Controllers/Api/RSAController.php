<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\ApiHelper as responseInterface;

class RSAController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $file = $request->file('file');
            Storage::putFileAs('', $file,config('services.bri.key').'.key');
            return ResponseInterface::resultResponse([
                'responseData' => "200",
                'responseMessage' => "success",
            ]);
        } catch (\Throwable $th) {
            return ResponseInterface::setErrorResponse($th);
        }
    }

    public function uploadMandiri(Request $request)
    {
        try {
            $file = $request->file('file');
            Storage::putFileAs('', $file,config('services.mandiri.key').'.key');
            return ResponseInterface::resultResponse([
                'responseData' => "200",
                'responseMessage' => "success",
            ]);
        } catch (\Throwable $th) {
            return ResponseInterface::setErrorResponse($th);
        }
    }
}
