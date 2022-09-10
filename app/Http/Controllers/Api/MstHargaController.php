<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Query\MstHarga;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Models\MstHarga as ModelsMstHarga;
use App\Services\User as Service;

class MstHargaController extends Controller
{
    public function index(Request $request)
    {
        return ResponseInterface::resultResponse(
            MstHarga::getAll($request)
        );
    }
}
