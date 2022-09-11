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

    public function updateFcmToken(Request $request) {
        try {
            $user = User::find($request->current_user->id);
            $user->fcm_token = $request->fcm_token;
            $user->save();

            return ResponseInterface::resultResponse(
                $user
            );

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function notification(Request $request){
        try{
            $fcmTokens = User::whereNotNull('fcm_token')->where('id',$request->current_user->id)->pluck('fcm_token')->toArray();
    
            // //Notification::send(null,new SendPushNotification($request->title,$request->message,$fcmTokens));
    
            // /* or */
    
            // //auth()->user()->notify(new SendPushNotification($title,$message,$fcmTokens));
    
            // /* or */
    
            // Larafirebase::withTitle($request->title)
            //     ->withBody($request->message)
            //     ->sendMessage($fcmTokens);
    
            // return ResponseInterface::createResponse(200,'Notification Sent Successfully!!',true);

            return Larafirebase::withTitle('Test Title '.$request->current_user->username)
            ->withBody('Test body')
            // ->withImage('https://firebase.google.com/images/social.png')
            ->withIcon('https://seeklogo.com/images/F/firebase-logo-402F407EE0-seeklogo.com.png')
            ->withSound('default')
            ->withClickAction('https://www.google.com')
            ->withPriority('high')
            ->withAdditionalData([
                'color' => '#rrggbb',
                'badge' => 0,
            ])
            ->sendNotification($fcmTokens);
        
            // Or
            // return Larafirebase::fromArray(['title' => 'Test Title '.$request->current_user->username, 'body' => 'Test body'])->sendNotification($fcmTokens);
        
            
        }catch(\Exception $e){
            report($e);
            return ResponseInterface::createResponse(400,'Something goes wrong while sending notification.',false);
        }
    }
}
