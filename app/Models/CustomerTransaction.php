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
        'created_at',
        'updated_at'
    ];
}
