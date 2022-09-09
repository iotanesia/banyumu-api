<?php

namespace App\Patners;

use Illuminate\Support\Facades\Http;
use App\Constants\Snap;
use Illuminate\Support\Facades\Log;
use App\ApiHelper as Helper;
class Mandiri {

    public static function getAccessToken($param)
    {
        try {
            $response = Http::timeout(10)
            ->withoutVerifying()
            ->withHeaders([
                'X-CLIENT-KEY' => $param['id_key'],
                'X-SIGNATURE' => $param['signature'],
                'X-TIMESTAMP' => $param['timestamp']
            ])
            ->contentType("application/json")
            ->post(config('services.mandiri.host').'/openapi/auth/v2.0/access-token/b2b',[
                'grantType' => 'client_credentials'
            ]);
            Log::info(json_encode($response->json()));
            if($response->getStatusCode() != 200) throw new \Exception(json_encode($response->json()), $response->getStatusCode());
            return $response->json();
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public static function snapService($param)
    {
        try {
            $response = Http::timeout(10)
            ->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer '.$param['auth'],
                'X-SIGNATURE' => $param['signature'],
                'X-TIMESTAMP' => $param['timestamp'],
                'X-PARTNER-ID' => $param['partnerId'],
                'X-EXTERNAL-ID' => $param['externalId'],
                'CHANNEL-ID' => $param['channelId']
            ])
            ->contentType("application/json")
            ->post(config('services.mandiri.host').$param['url'], $param['body']);
            Log::info([
                'Authorization' => 'Bearer '.$param['auth'],
                'X-SIGNATURE' => $param['signature'],
                'X-TIMESTAMP' => $param['timestamp'],
                'X-PARTNER-ID' => $param['partnerId'],
                'X-EXTERNAL-ID' => $param['externalId'],
                'CHANNEL-ID' => $param['channelId']
            ]);
            if($response->getStatusCode() != 200) throw new \Exception(json_encode($response->json()), $response->getStatusCode());
            return $response->json();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
