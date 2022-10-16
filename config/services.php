<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'bri' => [
        'host' => env('BRI_HOST','https://sandbox.partner.api.bri.co.id'),
        'key' => env('BRI_KEY_PRIVATE_NAME'),
    ],
    'mandiri' => [
        'host' => env('MANDIRI_HOST','https://mandiri-snap.linkaja.dev:4101'),
        'key' => env('MANDIRI_KEY_PRIVATE_NAME','mandiri'),
    ],
    'xendit' => [
        'secret_key' => env('XENDIT_KEY','xnd_production_9EiXubDOx4qDb22GLRf2F3lAKWLhvFQIpCkrSBUwl7VIAA1WPn2CmcE1M6gmtl'),
        'callback_secret_key' => env('CALLBACK_XENDIT_KEY','2f694544b3ebcd2eba1d39b00742e94192ba77da958b24e82092f8c53f5fbc4f')
    ],
    'banyumu' => [
        'userId' => env('USERID_BANYUMU','banyumu'),
        'password' => env('PASSWORD_BANYUMU','banyumu321'),
        'host' => env('LOCALHOST','127.0.0.1:3000')
    ]

];
