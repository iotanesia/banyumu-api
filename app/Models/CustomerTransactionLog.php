<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTransactionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_transaction_id',
        'action_by',
        'status',
        'created_at',
        'updated_at'
    ];

    public function refActionBy(){
        return $this->belongsTo(User::class,'action_by','id');
    }

}
