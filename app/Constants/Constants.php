<?php

namespace App\Constants;


class Constants
{
    const IS_ACTIVE = 1;
    const IS_NOL = 0;
    //transaksi
    const THP_PEMESANAN = 1;
    const STS_PEMESANAN = 'Pemesanan Baru';
    const STS_PEMESANAN_FB = 'pemesanan_baru';
    const THP_PEMBAYARAN = 2;
    const STS_PEMBAYARAN = 'Pembayaran Berhasil';
    const STS_PEMBAYARAN_QR = 'Pembayaran Berhasil_qr';
    const STS_PEMBAYARAN_FB = 'pembayaran_berhasil';
    const THP_PROSES = 3;
    const STS_PROSES = 'Proses pengisian';
    const STS_PROSES_FB = 'proses_pengisian_ulang';
    const THP_SELESAI = 4;
    const STS_SELESAI = 'Pengisian selesai';
    const STS_AIR_SELESAI = 'Pengisian Air Selesai';
    const STS_SELESAI_FB = 'pengisian_selesai';
    const THP_BATAL = 5;
    const STS_BATAL = 'Pembatalan transaksi';
    const STS_BATAL_FB = 'pembatalan_transaksi';
    //group
    const MESIN = 1;
    const ADMIN = 2;
}

