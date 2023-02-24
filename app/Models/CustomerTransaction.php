<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kapasitas',
        'harga',
        'tahap',
        'status',
        'flag_stiker',
        'code',
        'created_at',
        'updated_at'
    ];

    public function refMesin(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function manyLog(){
        return $this->hasMany(CustomerTransactionLog::class,'customer_transaction_id','id');
    }
    protected static function boot() {
        static::creating(function($model)
        {
            $lastCodeId = CustomerTransaction::max('id');
            $code = str_repeat("0", 4 - strlen($lastCodeId)).($lastCodeId + 1);
            if(!$model->flag_stiker) {
                $model->code = 'TRX-'.date('YmdHis').$code;
            }
        });

        parent::boot();
    }
}
