<?php

namespace App\Query;
use App\Models\CustomerTransaction as Model;
use App\ApiHelper as Helper;
use App\Constants\Constants;
use Illuminate\Support\Facades\DB;

class CustomerTransaction {
    public static function getAll($request)
    {
        try {
            if($request->dropdown == Constants::IS_ACTIVE) $request->limit = Model::count();
            $data = Model::where(function ($query) use ($request){
                if($request->current_user->is_group == Constants::ADMIN) {
                    $query->where('user_id',$request->current_user->mesin_id);

                } else {
                    $query->where('user_id',$request->current_user->id);
                }
                if($request->nama) $query->where('kapasitas','ilike',"%$request->nama%");
            })->paginate($request->limit);
                return [
                    'items' => $data->getCollection()->transform(function ($item){
                        return $item;
                    }),
                    'attributes' => [
                        'total' => $data->total(),
                        'current_page' => $data->currentPage(),
                        'from' => $data->currentPage(),
                        'per_page' => (int) $data->perPage(),
                    ]
                ];
        } catch (\Throwable $th) {
            throw $th;
        }
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
            DB::commit();
            return ['items' => $insert];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
