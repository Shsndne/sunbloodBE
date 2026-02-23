<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RumahSakit extends Model
{
    use HasFactory;

    protected $table = 'rumah_sakits';

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'email',
        'kota'
    ];

    // Relasi dengan stok darah
    public function stokDarah()
    {
        return $this->hasMany(StokDarah::class, 'rumah_sakit_id');
    }

    // Relasi dengan permintaan darah
    public function permintaanDarah()
    {
        return $this->hasMany(PermintaanDarah::class, 'rumah_sakit_id');
    }
}