<?php

use App\Http\Controllers\Api\AuthControler;
use App\Http\Controllers\Api\CustomerTransactionController;
use App\Http\Controllers\Api\MstHargaController;
use App\Http\Controllers\Api\Mandiri\MandiriController;
use App\Http\Controllers\Api\RSAController;
use App\Http\Controllers\Api\UserControler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('health', HealthCheckJsonResultsController::class);

//with middleware
Route::prefix('v1')
->namespace('Api')
->middleware('write.log')
->group(function () {

    Route::post('login',[AuthControler::class,'login']);
    Route::get('transaksi/update-pengisian-selesai',[CustomerTransactionController::class,'prosesPengisianSelesai']);
    Route::get('img-qr',[CustomerTransactionController::class,'imgQr']);
    
    Route::group(['middleware' => 'access'],function () {
        Route::post('update-fcm-token',[AuthControler::class,'updateFcmToken']);
        Route::post('test-notif',[AuthControler::class,'notification']);
        Route::prefix('transaksi')->group(function () {
            Route::get('/',[CustomerTransactionController::class,'index']);
            Route::get('/{id}',[CustomerTransactionController::class,'detail']);
            Route::post('/proses',[CustomerTransactionController::class,'proses']);
            Route::post('/update-pembayaran-admin/{id}',[CustomerTransactionController::class,'prosesPembayaranAdmin']);
            Route::post('/update-pengisian-air/{id}',[CustomerTransactionController::class,'prosesPengisianAir']);
            Route::post('/update-pengisian-ulang/{id}',[CustomerTransactionController::class,'prosesPengisianUlang']);
            Route::post('/update-pembatalan-transaksi/{id}',[CustomerTransactionController::class,'prosesPembatalanTransaksi']);
        });
        Route::prefix('master')->group(function () {
            Route::get('/harga',[MstHargaController::class,'index']);
        });
    });
});

