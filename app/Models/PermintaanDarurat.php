<?php
// app/Models/PermintaanDarurat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PermintaanDarurat extends Model
{
    use SoftDeletes;

    protected $table = 'permintaan_darurats';

    protected $fillable = [
        // Kolom baru (dari JS frontend)
        'nomor_resi',
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
        'pernyataan_setuju',
        'status',
        'status_pemenuhan',
        'catatan',

        // Kolom lama (backward compat)
        'kode',
        'usia',
        'gender',
        'jumlah',
        'deadline',
        'nama_rs',
        'alamat_rs',
        'kontak',
        'rumah_sakit_id',
    ];

    protected $casts = [
        'deadline'           => 'datetime',
        'tanggal_dibutuhkan' => 'date',
        'usia'               => 'integer',
        'usia_pasien'        => 'integer',
        'jumlah'             => 'integer',
        'jumlah_kantong'     => 'integer',
        'pernyataan_setuju'  => 'boolean',
    ];

    /**
     * Generate nomor resi unik format: SB-YYYYMMDD-XXXXXX
     */
    public static function generateNomorResi(): string
    {
        do {
            $resi = 'SB-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('nomor_resi', $resi)->exists());

        return $resi;
    }

    /**
     * Generate kode lama (backward compat)
     */
    public static function generateKode(): string
    {
        $prefix = 'SBD-' . date('Ymd') . '-';
        $count  = static::where('kode', 'like', $prefix . '%')->count() + 1;
        return $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Getter: label status
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu'  => 'Menunggu',
            'pending'   => 'Menunggu',
            'diproses'  => 'Diproses',
            'selesai'   => 'Selesai',
            'terpenuhi' => 'Terpenuhi',
            'ditolak'   => 'Ditolak',
            default     => ucfirst($this->status ?? '-'),
        };
    }

    /**
     * Getter: warna badge status
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'menunggu', 'pending' => 'warning',
            'diproses'            => 'info',
            'selesai', 'terpenuhi'=> 'success',
            'ditolak'             => 'danger',
            default               => 'secondary',
        };
    }

    /**
     * Getter: warna badge urgensi
     */
    public function getUrgensiColorAttribute(): string
    {
        return match($this->tingkat_urgensi ?? '') {
            'darurat'   => 'danger',
            'normal'    => 'warning',
            'terjadwal' => 'info',
            default     => 'secondary',
        };
    }

    /**
     * Relasi ke RumahSakit (opsional)
     */
    public function rumahSakit()
    {
        return $this->belongsTo(RumahSakit::class, 'rumah_sakit_id');
    }
}
