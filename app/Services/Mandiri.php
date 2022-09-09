<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Constants\Snap;
use Illuminate\Support\Facades\Log;
use App\ApiHelper as Helper;
use App\Patners\Mandiri as Patner;

class Mandiri {

    public static function authenticate($request)
    {
        try {
            $date = Helper::getDateNow();
            $private_key = Storage::get(config('services.mandiri.key').'.key');
            $stringToSign = Snap::CLIENT_ID_MANDIRI."|".$date;
            $binary_signature="";
            openssl_sign($stringToSign, $binary_signature, $private_key, 'SHA256');
            $signature =base64_encode($binary_signature);
            $param = [
                'signature' => $signature,
                'timestamp' => $date,
                'id_key' => Snap::CLIENT_ID_MANDIRI
            ];
            $response = Patner::getAccessToken($param);
            $response['timestamp'] = $date;
            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function hex64($signature) {
        return bin2hex(base64_decode($signature));
    }

    static function hex_to_base64($hex){
        $return = '';
        foreach(str_split($hex, 2) as $pair){
          $return .= chr(hexdec($pair));
        }
        return base64_encode($return);
    }

    public static function access($request, $url)
    {
        try {

            $auth = self::authenticate($request);
            $params = [
                'timeStamp' => $auth['timestamp'],
                'url' => $url,
                'request' => $request,
                'token' => $auth['accessToken']
            ];
            $secondSignature = self::generateSecondSignature($params);
            $signature = base64_encode(((hash_hmac('sha512', $secondSignature, snap::CLIENT_SECRET_MANDIRI, true))));
            // dd($signature);
            $param = [
                'signature' => $signature,
                'externalId' => $request->eksternalid,
                'partnerId' => Snap::PATNER_ID_MANDIRI,
                'auth' => $params['token'],
                'channelId' => 87899,
                'body' => $request->all(),
                'timestamp' => $params['timeStamp'],
                'url' => $url
            ];
            return Patner::snapService($param);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function generateSecondSignature($param)
    {
        $body = $param['request']->all();
        $minify = json_encode($body,true);
        $hexstring = strtolower(hash('sha256', $minify));
        // $hexstring = '7647dcbe67725d6a6fe161de96d77ab103e0c624d5198bd982560fbea78c96fb';
        $payload = $param['request']->getMethod().':'.$param['url'].':'.$param['token'].':'.(string) $hexstring.':'.$param['timeStamp'];
        return $payload;
    }
}
