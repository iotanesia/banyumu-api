<?php

namespace App\Constants;


class Constants
{
    const IS_ACTIVE = 1;
    const IS_NOL = 0;
    //transaksi
    const THP_PEMESANAN = 1;
    const STS_PEMESANAN = 'Pesan';
    const THP_PEMBAYARAN = 2;
    const STS_PEMBAYARAN = 'Pembayaran Berhasil';
    const THP_PROSES = 3;
    const STS_PROSES = 'Proses pengisian';
    const THP_SELESAI = 4;
    const STS_SELESAI = 'Pengisian selesai';
    //group
    const MESIN = 1;
    const ADMIN = 2;
}

