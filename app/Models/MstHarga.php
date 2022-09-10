<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstHarga extends Model
{
    use HasFactory;

    protected $table = 'mst_harga';
    protected $fillable = [
        'kapasitas',
        'harga',
        'satuan',
        'updated_at',
        'updated_at',
    ];
}
