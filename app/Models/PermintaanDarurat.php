<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanDarurat extends Model
{
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
        'rumah_sakit_id',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'usia'     => 'integer',
        'jumlah'   => 'integer',
    ];

    // Default status saat dibuat
    protected $attributes = [
        'status'           => 'menunggu',
        'status_pemenuhan' => 'belum_terpenuhi',
    ];

    // Relasi ke tabel rumah_sakits (opsional jika ada)
    public function rumahSakit(): BelongsTo
    {
        return $this->belongsTo(RumahSakit::class, 'rumah_sakit_id');
    }

    // Generate kode unik otomatis
    public static function generateKode(): string
    {
        $prefix = 'SBD-' . date('Ymd') . '-';
        $last   = self::where('kode', 'like', $prefix . '%')->count() + 1;
        return $prefix . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    // Label badge status
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu'  => 'Menunggu',
            'diproses'  => 'Diproses',
            'selesai'   => 'Selesai',
            'ditolak'   => 'Ditolak',
            default     => ucfirst($this->status),
        };
    }
}