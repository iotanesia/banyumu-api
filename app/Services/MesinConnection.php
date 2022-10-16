<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MesinConnection {
    public static function turnOn($param)
    {
        try {
            $response = Http::timeout(10)
            ->withoutVerifying()
            ->contentType("application/json")
            ->get('https://api.thingspeak.com/update?api_key=Q4QD7S1ARAHYELT8&amp;amp;field2=1');
            Log::info($response);
            if($response == '0') {
                self::turnOn('Q4QD7S1ARAHYELT8');
            } else {
                return $response;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public static function turnOnV2($param)
    {
        try {
            $response = Http::timeout(5)
            ->withHeaders([
                'Authorization' => 'Basic '.base64_encode(config('services.banyumu.userId').':'.config('services.banyumu.password'))
            ])
            ->contentType("application/json")
            ->post(config('services.banyumu.host').'/api/v1/function/indoor', $param['body']);
            Log::info('Masuk node server');
            Log::info(json_encode($response->json()));
            if($response->getStatusCode() != 200) throw new \Exception(json_encode($response->json()), $response->getStatusCode());
            return $response->json();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public static function updateDebit($debit)
    {
        try {
            $response = Http::timeout(10)
            ->withoutVerifying()
            ->contentType("application/json")
            ->get('https://api.thingspeak.com/update?api_key=Q4QD7S1ARAHYELT8&amp;amp;field1='.$debit);
            Log::info($response);
            if($response == '0') {
                self::turnOn('Q4QD7S1ARAHYELT8');
            } else {
                return $response;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}