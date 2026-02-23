<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanDarurat extends Model
{
    use HasFactory;

    protected $table = 'permintaan_darurats';

    protected $fillable = [
        'kode',
        'nama_pasien',
        'usia',
        'gender',
        'diagnosis',
        'golongan_darah',
        'jumlah',
        'deadline',
        'status',
        'status_pemenuhan',
        'nama_rs',
        'alamat_rs',
        'kontak',
        'nama_kontak',
        'catatan',
        'rumah_sakit_id'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'jumlah' => 'integer',
        'usia' => 'integer'
    ];

    /**
     * Relasi dengan rumah sakit
     */
    public function rumahSakit()
    {
        return $this->belongsTo(RumahSakit::class, 'rumah_sakit_id');
    }

    /**
     * Scope untuk filter status darurat
     */
    public function scopeDarurat($query)
    {
        return $query->where('status', 'DARURAT');
    }

    /**
     * Scope untuk filter status pemenuhan
     */
    public function scopeBelumDiproses($query)
    {
        return $query->where('status_pemenuhan', 'belum');
    }

    /**
     * Scope untuk filter deadline mendekati
     */
    public function scopeNearDeadline($query, $hours = 2)
    {
        return $query->where('deadline', '<=', now()->addHours($hours))
            ->where('deadline', '>', now())
            ->where('status_pemenuhan', '!=', 'terpenuhi');
    }

    /**
     * Cek apakah status darurat
     */
    public function getIsDaruratAttribute()
    {
        return $this->status === 'DARURAT';
    }

    /**
     * Cek apakah sudah terlewat deadline
     */
    public function getIsOverdueAttribute()
    {
        return $this->deadline < now() && $this->status_pemenuhan !== 'terpenuhi';
    }

    /**
     * Mendapatkan warna status
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'DARURAT' => 'danger',
            'NORMAL' => 'primary',
            'TERENCANA' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Mendapatkan warna status pemenuhan
     */
    public function getPemenuhanColorAttribute()
    {
        return match($this->status_pemenuhan) {
            'belum' => 'danger',
            'diproses' => 'warning',
            'terpenuhi' => 'success',
            default => 'secondary'
        };
    }
}