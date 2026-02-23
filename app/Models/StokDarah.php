<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokDarah extends Model
{
    use HasFactory;

    protected $table = 'stok_darah';

    protected $fillable = [
        'nama_rs',
        'foto',
        'stok_a',
        'stok_b',
        'stok_ab',
        'stok_o'
    ];

    protected $casts = [
        'stok_a' => 'integer',
        'stok_b' => 'integer',
        'stok_ab' => 'integer',
        'stok_o' => 'integer'
    ];

    /**
     * Get total stok
     */
    public function getTotalStokAttribute()
    {
        return $this->stok_a + $this->stok_b + $this->stok_ab + $this->stok_o;
    }

    /**
     * Get status stok
     */
    public function getStatusAttribute()
    {
        $total = $this->total_stok;
        
        if ($total < 30) {
            return 'Kritis';
        } elseif ($total < 70) {
            return 'Sedang';
        } else {
            return 'Aman';
        }
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute()
    {
        $total = $this->total_stok;
        
        if ($total < 30) {
            return 'danger';
        } elseif ($total < 70) {
            return 'warning';
        } else {
            return 'success';
        }
    }
}