<?php

namespace App\Http\Controllers\Api\Bri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Services\Bri;
class BriController extends Controller
{
    public function signatureAuth(Request $request)
    {
        try {
            return ResponseInterface::resultResponse(
                Bri::authenticate($request)
            );
        } catch (\Throwable $th) {
            return ResponseInterface::setErrorResponse($th);
        }
    }

    public function accountInquiryInternal(Request $request)
    {
        try {
            return ResponseInterface::resultResponse(
                Bri::access($request, '/intrabank/snap/v1.0/account-inquiry-internal')
            );
        } catch (\Throwable $th) {
            return ResponseInterface::setErrorResponse($th);
        }
    }

    public function accountInquiryStatus(Request $request)
    {
        try {
            return ResponseInterface::resultResponse(
                Bri::access($request, '/snap/v1.0/transfer/status')
            );
        } catch (\Throwable $th) {
            return ResponseInterface::setErrorResponse($th);
        }
    }

    public function transferIntrabank(Request $request)
    {
        try {
            return ResponseInterface::resultResponse(
                Bri::access($request, '/intrabank/snap/v1.0/transfer-intrabank')
            );
        } catch (\Throwable $th) {
            return ResponseInterface::setErrorResponse($th);
        }
    }
}
