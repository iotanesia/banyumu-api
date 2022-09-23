<?php

namespace App\Query;
use App\Models\CustomerTransaction as Model;
use App\Models\CustomerTransactionLog as Log;
use App\ApiHelper as Helper;
use App\Constants\Constants;
use App\Models\User;
use App\Notif;
use App\Services\MesinConnection;
use App\Services\XenditServices;
use Illuminate\Support\Facades\DB;

class CustomerTransaction {
    public static function getAll($request)
    {
        if($request->dropdown == Constants::IS_ACTIVE) $request->limit = Model::count();
        $data = Model::where(function ($query) use ($request){
            if($request->current_user->is_group == Constants::ADMIN) {
                $query->where('user_id',$request->current_user->mesin_id);

            } else {
                $query->where('user_id',$request->current_user->id);
            }
            if($request->nama) $query->where('kapasitas','ilike',"%$request->nama%");
        })->orderBy('created_at','desc')->paginate($request->limit);
        return [
            'items' => $data->getCollection()->transform(function ($item){
                $item['mesin_code'] = $item->refMesin->mesin_code ?? null;
                $item['mesin_name'] = $item->refMesin->username ?? null;
                $item['mesin_id'] = $item->refMesin->id ?? null;
                $item['transaction_code'] = $item->code ?? 'TRX-'.date('dmYHis',strToTime($item->created_at)).'00'.$item->id;
                unset($item->refMesin);
                return $item;
            }),
            'attributes' => [
                'total' => $data->total(),
                'current_page' => $data->currentPage(),
                'from' => $data->currentPage(),
                'per_page' => (int) $data->perPage(),
            ]
        ];
    }

    public static function detail($id) {
        $data = Model::find($id);
        $data->log = $data->manyLog->transform(function ($item){
            $item['admin_name'] = $item->refActionBy->username ?? null;
            unset($item->refActionBy);
            return $item;
        });
        unset($data->manyLog);
        return ['items' => $data];
    }

    public static function reserveWater($param)
    {
        DB::beginTransaction();
        try {
            $data = $param->all();
            $data['user_id'] = $param->current_user->id;
            $data['tahap'] = Constants::THP_PEMESANAN;
            $data['status'] = Constants::STS_PEMESANAN;
            $insert = Model::create($data);

            $dataSend['external_id'] = 'trx-'.$insert->id;
            $dataSend['type'] = 'DYNAMIC';
            $dataSend['callback_url'] = 'https://my-shop.com/callbacks';
            $dataSend['amount'] = $insert->harga;
            $insert->qr_data = XenditServices::createQR($dataSend);

            // dd($insert->qr_data);
            Log::create(self::setParamLog($data,$insert));
            DB::commit();
            return ['items' => $insert];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function setParamLog($param,$data) {
        $dataArr['customer_transaction_id'] = $data->id;
        $dataArr['action_by'] = $param['action_by'] ?? $param['user_id'];
        $dataArr['status'] = $param['status'];
        return $dataArr;
    }

    public static function adminPaymentProcess($param,$id)
    {
        DB::beginTransaction();
        try {
            $data = $param->all();
            $data['action_by'] = $param->current_user->id;
            $data['tahap'] = Constants::THP_PEMBAYARAN;
            $data['status'] = Constants::STS_PEMBAYARAN;
            $update = Model::find($id);
            $update->fill($data);
            $update->save();
            Log::create(self::setParamLog($data,$update));
            $notif['title'] = 'Pembayaran Berhasil';
            $notif['body'] = 'Pembayaran Berhasil '.$param->current_user->username;
            Notif::sendNotif($param,$notif,['status' => Constants::STS_PEMBAYARAN_FB]);
            $mesin = User::whereNotNull('api_key')->find($param->current_user->mesin_id);
            // if(!$mesin) throw new \Exception('Api Key belum terdaftar', 500);
            MesinConnection::updateDebit($update->kapasitas);
            MesinConnection::turnOn($mesin->api_key);
            DB::commit();
            return ['items' => $update];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function callbackApi($param)
    {
        DB::beginTransaction();
        try {
            $data = $param->all();
            // $data['action_by'] = $param->current_user->id;
            // $data['tahap'] = Constants::THP_PEMBAYARAN;
            // $data['status'] = Constants::STS_PEMBAYARAN;
            // $update = Model::find($param);
            // $update->fill($data);
            // $update->save();
            // Log::create(self::setParamLog($data,$update));
            // $notif['title'] = 'Pembayaran Berhasil';
            // $notif['body'] = 'Pembayaran Berhasil '.$param->current_user->username;
            // Notif::sendNotif($param,$notif,['status' => Constants::STS_PEMBAYARAN_FB]);
            // $mesin = User::whereNotNull('api_key')->find($param->current_user->mesin_id);
            // MesinConnection::updateDebit($update->kapasitas);
            // MesinConnection::turnOn($mesin->api_key);
            // DB::commit();
            return ['items' => $data];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function waterFilling($param,$id)
    {
        DB::beginTransaction();
        try {
            $data = $param->all();
            $data['action_by'] = $param->current_user->id;
            $data['tahap'] = Constants::THP_SELESAI;
            $data['status'] = Constants::STS_SELESAI;
            $update = Model::find($id);
            $update->fill($data);
            $update->save();
            Log::create(self::setParamLog($data,$update));
            DB::commit();
            $notif['title'] = 'Pengisian Air Selesai';
            $notif['body'] = 'Pengisian Air Selesai '.$param->current_user->username;
            Notif::sendNotif($param,$notif,['status' => Constants::STS_SELESAI_FB]);
            return ['items' => $update];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function waterFillingFinished($param)
    {
        DB::beginTransaction();
        try {
            $data = $param->all();
            $data['action_by'] = 3;
            $data['tahap'] = Constants::THP_SELESAI;
            $data['status'] = Constants::STS_SELESAI;
            $update = Model::where('user_id',3)->orderBy('id','desc')->where('status','Pembayaran Berhasil')->first();
            if(!$update) return 0;
            $update->fill($data);
            $update->save();
            Log::create(self::setParamLog($data,$update));
            DB::commit();
            $notif['title'] = 'Pengisian Air Selesai';
            $notif['body'] = 'Pengisian Air Selesai '.'sariater001';
            Notif::sendNotifSementara($param,$notif,['status' => Constants::STS_SELESAI_FB]);
            return ['items' => $update];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function reloadWater($param,$id)
    {
        DB::beginTransaction();
        try {
            $data = $param->all();
            $data['action_by'] = $param->current_user->id;
            $data['tahap'] = Constants::THP_PROSES;
            $data['status'] = Constants::STS_PROSES;
            $update = Model::find($id);
            $update->fill($data);
            $update->save();
            Log::create(self::setParamLog($data,$update));
            DB::commit();
            $notif['title'] = 'Proses Pengisian Air';
            $notif['body'] = 'Proses Pengisian Air '.$param->current_user->username;
            Notif::sendNotif($param,$notif,['status' => Constants::STS_PROSES_FB]);
            return ['items' => $update];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function cancelTransaksi($param,$id)
    {
        DB::beginTransaction();
        try {
            $data = $param->all();
            $data['action_by'] = $param->current_user->id;
            $data['tahap'] = Constants::THP_BATAL;
            $data['status'] = Constants::STS_BATAL;
            $update = Model::find($id);
            $update->fill($data);
            $update->save();
            Log::create(self::setParamLog($data,$update));
            DB::commit();
            return ['items' => $update];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
