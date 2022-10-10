<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Query\CustomerTransaction;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Services\User as Service;
use Illuminate\Support\Facades\Log;

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
        Log::info('masuk');
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
        return ResponseInterface::resultResponseMesin(
            CustomerTransaction::waterFillingFinished($request)
        );
    }
    public function prosesPembatalanTransaksi(Request $request,$id)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::cancelTransaksi($request,$id)
        );
    }
    public function imgQr(Request $request)
    {
        return ResponseInterface::resultResponse(
            ['items' => ['img_qr'=>'BCA000201010211500201511027301632515204000053033605802ID5908WAN ADLI6003BCA62380216bXVufonIBXCLZPne991400303617092022630497d9']]
        );
    }
    public function callbackApi(Request $request)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::callbackApi($request)
        );
    }
    public function qrTes(Request $request)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::qrTes($request)
        );
    }
}
