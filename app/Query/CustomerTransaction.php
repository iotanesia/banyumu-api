<?php

namespace App\Query;
use App\Models\CustomerTransaction as Model;
use App\Models\CustomerTransactionLog as Log;
use App\ApiHelper as Helper;
use App\Constants\Constants;
use App\Notif;
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
            DB::commit();
            $notif['title'] = 'Pembayaran Berhasil';
            $notif['body'] = 'Pembayaran Berhasil '.$param->current_user->username;
            Notif::sendNotif($param,$notif);
            return ['items' => $update];
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
            Notif::sendNotif($param,$notif);
            return ['items' => $update];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
