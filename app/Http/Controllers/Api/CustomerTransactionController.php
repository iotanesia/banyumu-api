<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Query\CustomerTransaction;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Services\User as Service;

class CustomerTransactionController extends Controller
{
    public function index(Request $request)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::getAll($request)
        );
    }
    public function detail(Request $request,$id)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::detail($id)
        );
    }
    public function proses(Request $request)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::reserveWater($request)
        );
    }
    public function prosesPembayaranAdmin(Request $request,$id)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::adminPaymentProcess($request,$id)
        );
    }
    public function prosesPengisianAir(Request $request,$id)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::waterFilling($request,$id)
        );
    }
    public function prosesPengisianUlang(Request $request,$id)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::reloadWater($request,$id)
        );
    }
    public function prosesPengisianSelesai(Request $request)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::waterFillingFinished($request)
        );
    }
}
