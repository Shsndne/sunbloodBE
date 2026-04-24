<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermintaanDarurat extends Model
{
    use HasFactory;

    protected $table = 'permintaan_darurat';

    protected $fillable = [
        'nama_pasien',
        'usia_pasien',
        'jenis_kelamin',
        'diagnosis',
        'golongan_darah',
        'rhesus',
        'jumlah_kantong',
        'tanggal_dibutuhkan',
        'tingkat_urgensi',
        'nama_rumah_sakit',
        'alamat_lengkap',
        'nama_kontak',
        'telepon_kontak',
        'nomor_resi',
        'status',
        'pernyataan_setuju',
    ];

    protected $casts = [
        'tanggal_dibutuhkan' => 'date',
        'pernyataan_setuju'  => 'boolean',
        'usia_pasien'        => 'integer',
        'jumlah_kantong'     => 'integer',
    ];
}