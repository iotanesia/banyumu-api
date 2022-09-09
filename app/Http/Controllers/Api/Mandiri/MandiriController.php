<?php

namespace App\Http\Controllers\Api\Mandiri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Services\Mandiri;
class MandiriController extends Controller
{
    public function signatureAuth(Request $request)
    {
        try {
            return ResponseInterface::resultResponse(
                Mandiri::authenticate($request)
            );
        } catch (\Throwable $th) {
            return ResponseInterface::setErrorResponse($th);
        }
    }

    public function accountInquiryInternal(Request $request)
    {
        try {
            $request->eksternalid = ResponseInterface::setEksternalId();
            return ResponseInterface::resultResponse(
                Mandiri::access($request, '/openapi/customers/v2.0/account-inquiry-internal')
            );
        } catch (\Throwable $th) {
            return ResponseInterface::setErrorResponse($th);
        }
    }

    public function accountInquiryStatus(Request $request)
    {
        try {
            $request->eksternalid = ResponseInterface::setEksternalId();
            return ResponseInterface::resultResponse(
                Mandiri::access($request, '/openapi/transactions/v2.0/transfer/status')
            );
        } catch (\Throwable $th) {
            return ResponseInterface::setErrorResponse($th);
        }
    }

    public function transferIntrabank(Request $request)
    {
        try {
            $request->eksternalid = ResponseInterface::setEksternalId();
            return ResponseInterface::resultResponse(
                Mandiri::access($request, '/openapi/transactions/v2.0/transfer-intrabank'),
                200,
                $request->eksternalid
            );
        } catch (\Throwable $th) {
            return ResponseInterface::setErrorResponse($th);
        }
    }
}
