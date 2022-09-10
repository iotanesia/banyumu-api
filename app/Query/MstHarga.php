<?php

namespace App\Query;
use App\Models\MstHarga as Model;
use App\ApiHelper as Helper;
use App\Constants\Constants;
use Illuminate\Support\Facades\DB;

class MstHarga {

    public static function byId($id)
    {
        return ['items' => Model::find($id)];
    }

    public static function getByName($nama)
    {
        $data =  Model::where('kapasitas', 'like', '%' . $nama . '%')->first();
        if (!empty($data)) {
            $datas = $data->id;
        } else {
            $datas = null;
        }

        return $datas;
    }
    
    public static function getAll($request)
    {
        try {
            return ['items' => Model::where('location_id',$request->current_user->location_id)->first()];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function store($request,$is_transaction = true)
    {
        if($is_transaction) DB::beginTransaction();
        try {

            $require_fileds = [];
            if(!$request->nama) $require_fileds[] = 'kapasitas';
            if(count($require_fileds) > 0) throw new \Exception('This parameter must be filled '.implode(',',$require_fileds),400);

            $store = Model::create($request->all());
            if($is_transaction) DB::commit();
            return $store;
        } catch (\Throwable $th) {
            if($is_transaction) DB::rollBack();
            throw $th;
        }
    }

    public static function updated($request,$id,$is_transaction = true)
    {
        if($is_transaction) DB::beginTransaction();
        try {
            $update = Model::find($id);
            if(!$update) throw new \Exception("Data not found.", 400);
            $update->update($request->all());
            if($is_transaction) DB::commit();
            return $update;
        } catch (\Throwable $th) {
            if($is_transaction) DB::rollBack();
            throw $th;
        }
    }

    public static function destroy($id,$is_transaction = true)
    {
        if($is_transaction) DB::beginTransaction();
        try {
            $delete = Model::destroy($id);
            if($is_transaction) DB::commit();
            return $delete;
        } catch (\Throwable $th) {
            if($is_transaction) DB::rollback();
            throw $th;
        }
    }
}
