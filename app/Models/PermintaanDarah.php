<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermintaanDarah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permintaan_darah';

    protected $fillable = [
        'rumah_sakit_id',
        'golongan_darah',
        'jumlah_kantong',
        'tingkat_kebutuhan',
        'status_pemenuhan',
        'keterangan',
        'tanggal_dibutuhkan',
        'jumlah_terpenuhi'
    ];

    protected $casts = [
        'tanggal_dibutuhkan' => 'date',
        'jumlah_kantong' => 'integer',
        'jumlah_terpenuhi' => 'integer'
    ];

    /**
     * Relasi ke rumah sakit
     */
    public function rumahSakit()
    {
        return $this->belongsTo(StokDarah::class, 'rumah_sakit_id');
    }

    /**
     * Cek apakah permintaan sudah terpenuhi semua
     */
    public function getIsTerpenuhiAttribute()
    {
        return $this->jumlah_terpenuhi >= $this->jumlah_kantong;
    }

    /**
     * Get sisa jumlah yang belum terpenuhi
     */
    public function getSisaAttribute()
    {
        return max(0, $this->jumlah_kantong - $this->jumlah_terpenuhi);
    }

    /**
     * Scope untuk permintaan darurat
     */
    public function scopeDarurat($query)
    {
        return $query->where('tingkat_kebutuhan', 'darurat')
                     ->where('status_pemenuhan', '!=', 'terpenuhi');
    }

    /**
     * Scope untuk permintaan yang belum dipenuhi
     */
    public function scopeBelumTerpenuhi($query)
    {
        return $query->whereIn('status_pemenuhan', ['belum', 'diproses', 'sebagian']);
    }
}