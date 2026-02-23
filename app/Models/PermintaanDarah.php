<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanDarah extends Model
{
    use HasFactory;

    protected $table = 'permintaan_darahs';

    protected $fillable = [
        'rumah_sakit_id',
        'golongan_darah',
        'jumlah_kantong',
        'status', // 'darurat' atau 'biasa'
        'status_pemenuhan', // 'belum', 'diproses', 'terpenuhi'
        'keterangan',
        'tanggal_dibutuhkan'
    ];

    protected $casts = [
        'tanggal_dibutuhkan' => 'date'
    ];

    // Relasi dengan rumah sakit
    public function rumahSakit()
    {
        return $this->belongsTo(RumahSakit::class);
    }

    // Scope untuk permintaan darurat
    public function scopeDarurat($query)
    {
        return $query->where('status', 'darurat');
    }

    // Scope untuk permintaan belum terpenuhi
    public function scopeBelumTerpenuhi($query)
    {
        return $query->where('status_pemenuhan', 'belum');
    }
}