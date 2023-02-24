<?php

namespace Database\Seeders;

use App\Constants\Constants;
use App\Models\CustomerTransaction;
use Illuminate\Database\Seeder;

class TrxStiker extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'user_id' => 16,
            'kapasitas' => 500,
            'harga' => 4000,
            'tahap' => Constants::THP_PEMBAYARAN,
            'status' => Constants::STS_PEMBAYARAN,
            'flag_stiker' => 1
        ];

        CustomerTransaction::create($data);
    }
}
