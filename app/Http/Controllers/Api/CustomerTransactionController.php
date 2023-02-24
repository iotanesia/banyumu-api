<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Query\CustomerTransaction;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Constants\Constants;
use App\Models\CustomerTransaction as ModelsCustomerTransaction;
use App\Models\User;
use App\Services\User as Service;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class CustomerTransactionController extends Controller
{
    public function index(Request $request)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::getAll($request)
        );
    }
    public function detail(Request $request,$id)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::detail($id)
        );
    }
    public function detailQr(Request $request,$code)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::detailByCode($code)
        );
    }
    public function proses(Request $request)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::reserveWater($request)
        );
    }
    public function prosesPembayaranAdmin(Request $request,$id)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::adminPaymentProcess($request,$id)
        );
    }
    public function prosesPembayaranQr(Request $request,$code)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::qrPaymentProcess($request,$code)
        );
    }
    public function prosesPengisianAir(Request $request,$id)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::waterFilling($request,$id)
        );
    }
    public function prosesPengisianUlang(Request $request,$id)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::reloadWater($request,$id)
        );
    }
    public function prosesPengisianSelesai(Request $request)
    {
        return ResponseInterface::resultResponseMesin(
            CustomerTransaction::waterFillingFinished($request)
        );
    }
    public function prosesPengisianSelesaiV2(Request $request)
    {
        return ResponseInterface::resultResponseMesin(
            CustomerTransaction::waterFillingFinishedV2($request)
        );
    }
    public function prosesPembatalanTransaksi(Request $request,$id)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::cancelTransaksi($request,$id)
        );
    }
    public function imgQr(Request $request)
    {
        // QrCode::format('png')->merge(url('/img/ic_icon_apps.png'), .3, true)->generate();
        QrCode::format('png')->size(300)->merge('/ic_icon_apps.jpeg')->margin(1)->generate(base64_encode($request->code_trx), 'qrcode_trx_file/'.$request->code_trx.'.png');
        return url('qrcode_file/'.$request->code_trx.'.png');
        // return ResponseInterface::resultResponse(
        //     ['items' => ['img_qr'=>'BCA000201010211500201511027301632515204000053033605802ID5908WAN ADLI6003BCA62380216bXVufonIBXCLZPne991400303617092022630497d9']]
        // );
    }
    public function pdfimgQr(Request $request)
    {
        DB::beginTransaction();
        $user = User::find($request->user_id);
        $data = CustomerTransaction::pdfQr();
        $countData = count($data);
        if($request->jumlah) {
            for ($i=0; $i < $request->jumlah; $i++) { 
                $code = str_pad($countData++,5,"0").'BSA'.$request->kapasitas.$user->device_id;
                $trx = [
                    'user_id' => $request->user_id,
                    'kapasitas' => $request->kapasitas,
                    'harga' => $request->kapasitas * $user->refMstHarga->harga,
                    'tahap' => Constants::THP_PEMBAYARAN,
                    'status' => Constants::STS_PEMBAYARAN,
                    'flag_stiker' => 1,
                    'code'=>$code
                ];
                $insert = ModelsCustomerTransaction::create($trx);
                QrCode::format('png')->size(300)->merge('/ic_icon_apps.jpeg')->margin(1)->generate(base64_encode($code), 'qrcode_trx_file/'.$code.'.png');
            }
        }    

        $data = CustomerTransaction::pdfQr();
        // QrCode::format('png')->size(300)->merge('/ic_icon_apps.jpeg')->margin(1)->generate(base64_encode($request->code_trx), 'qrcode_file/'.$request->code_trx.'.png');
        $rowData = ceil(count($data) / 4);
        $filename = 'trx2.pdf';
        $pathToFile = storage_path().'/app/public/qr_file/'.$filename;
        PDF::loadView('pdf.qr_file',[
            'data' => $data,
            'rowData' => $rowData,
            'colData' => 4
        ])
        ->setPaper('a4','potrait')
        ->save($pathToFile)
        ->download($filename);
        DB::commit();
        return ResponseInterface::responseViewFile($pathToFile,$filename);
    }
    public function callbackApi(Request $request)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::callbackApi($request)
        );
    }
    public function callbackApiV2(Request $request)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::callbackApiV2($request)
        );
    }
    public function qrTes(Request $request)
    {
        return ResponseInterface::resultResponse(
            CustomerTransaction::qrTes($request)
        );
    }
}
