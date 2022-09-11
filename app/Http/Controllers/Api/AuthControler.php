<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Models\User;
use App\Services\User as Service;
use App\Services\Signature;
use Illuminate\Support\Facades\File;
use Kutia\Larafirebase\Facades\Larafirebase;

class AuthControler extends Controller
{
    public function login(Request $request)
    {
        return ResponseInterface::resultResponse(
            Service::authenticateuser($request)
        );
    }

    public function notification(Request $request){
        $request->validate([
            'title'=>'required',
            'message'=>'required'
        ]);
    
        try{
            $fcmTokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
    
            //Notification::send(null,new SendPushNotification($request->title,$request->message,$fcmTokens));
    
            /* or */
    
            //auth()->user()->notify(new SendPushNotification($title,$message,$fcmTokens));
    
            /* or */
    
            Larafirebase::withTitle($request->title)
                ->withBody($request->message)
                ->sendMessage($fcmTokens);
    
            return ResponseInterface::createResponse(200,'Notification Sent Successfully!!',true);
            
        }catch(\Exception $e){
            report($e);
            return ResponseInterface::createResponse(400,'Something goes wrong while sending notification.',false);
        }
    }
}
