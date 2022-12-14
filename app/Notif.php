<?php

namespace App;

use App\Models\User;
use Kutia\Larafirebase\Facades\Larafirebase;
use App\ApiHelper as ResponseInterface;

class Notif {
    public static function sendNotif($request,$data,$dataSend = null) {
        try{
            $fcmTokens = User::whereNotNull('fcm_token')->where('id',$request->current_user->mesin_id)->pluck('fcm_token')->toArray();
            return Larafirebase::withTitle($data['title'])
                   ->withBody($data['body'])
                   ->withSound('default')
                   ->withPriority('high')
                   ->withAdditionalData($dataSend)
                       // ->withImage('https://firebase.google.com/images/social.png')
                       // ->withIcon('https://seeklogo.com/images/F/firebase-logo-402F407EE0-seeklogo.com.png')
                       // ->withClickAction('https://www.google.com')
                   ->sendNotification($fcmTokens);
        
            // Or
            // return Larafirebase::fromArray(['title' => 'Test Title '.$request->current_user->username, 'body' => 'Test body'])->sendNotification($fcmTokens);
        
        }catch(\Exception $e){
            report($e);
            return ResponseInterface::createResponse(400,'Something goes wrong while sending notification.',false);
        }
    }
    public static function sendNotifCallbacks($userId,$data,$dataSend = null) {
        try{
            $fcmTokens = User::whereNotNull('fcm_token')->where('id',$userId)->pluck('fcm_token')->toArray();
            return Larafirebase::withTitle($data['title'])
                   ->withBody($data['body'])
                   ->withSound('default')
                   ->withPriority('high')
                   ->withAdditionalData($dataSend)
                       // ->withImage('https://firebase.google.com/images/social.png')
                       // ->withIcon('https://seeklogo.com/images/F/firebase-logo-402F407EE0-seeklogo.com.png')
                       // ->withClickAction('https://www.google.com')
                   ->sendNotification($fcmTokens);
        
            // Or
            // return Larafirebase::fromArray(['title' => 'Test Title '.$request->current_user->username, 'body' => 'Test body'])->sendNotification($fcmTokens);
        
        }catch(\Exception $e){
            report($e);
            return ResponseInterface::createResponse(400,'Something goes wrong while sending notification.',false);
        }
    }
    public static function sendNotifSementara($request,$data,$dataSend = null) {
        try{
            $fcmTokens = User::whereNotNull('fcm_token')->where('id',3)->pluck('fcm_token')->toArray();
            return Larafirebase::withTitle($data['title'])
                   ->withBody($data['body'])
                   ->withSound('default')
                   ->withPriority('high')
                   ->withAdditionalData($dataSend)
                       // ->withImage('https://firebase.google.com/images/social.png')
                       // ->withIcon('https://seeklogo.com/images/F/firebase-logo-402F407EE0-seeklogo.com.png')
                       // ->withClickAction('https://www.google.com')
                   ->sendNotification($fcmTokens);
        
            // Or
            // return Larafirebase::fromArray(['title' => 'Test Title '.$request->current_user->username, 'body' => 'Test body'])->sendNotification($fcmTokens);
        
        }catch(\Exception $e){
            report($e);
            return ResponseInterface::createResponse(400,'Something goes wrong while sending notification.',false);
        }
    }
}