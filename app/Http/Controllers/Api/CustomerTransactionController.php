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
}
