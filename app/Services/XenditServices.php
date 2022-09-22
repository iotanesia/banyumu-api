<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Xendit\Xendit;
use Xendit\QRCode;

class XenditServices {
    public static function createQR($param)
    {
        Xendit::setApiKey(config('services.xendit.secret_key'));
        return QRCode::create($param);
    }
}
