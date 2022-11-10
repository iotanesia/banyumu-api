<?php

namespace App\Query;
use App\Models\MstKalibrasi as Model;
use App\ApiHelper as Helper;
use App\Constants\Constants;
use Illuminate\Support\Facades\DB;

class MstKalibrasi {

    public static function getTime($mesin,$kapasitas)
    {
        return Model::where('ml',$kapasitas)->where('user_id',$mesin->id)->first()->time;
    }
}
