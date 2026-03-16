<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokDarah extends Model
{
    use SoftDeletes;

    protected $table = 'stok_darah';

    protected $fillable = [
        'nama_rs',
        'foto',
        'stok_a_plus',
        'stok_a_minus',
        'stok_b_plus',
        'stok_b_minus',
        'stok_ab_plus',
        'stok_ab_minus',
        'stok_o_plus',
        'stok_o_minus',
    ];

    protected $casts = [
        'stok_a_plus'  => 'integer',
        'stok_a_minus' => 'integer',
        'stok_b_plus'  => 'integer',
        'stok_b_minus' => 'integer',
        'stok_ab_plus' => 'integer',
        'stok_ab_minus'=> 'integer',
        'stok_o_plus'  => 'integer',
        'stok_o_minus' => 'integer',
    ];

    // Total semua stok di rumah sakit ini
    public function getTotalStokAttribute(): int
    {
        return $this->stok_a_plus + $this->stok_a_minus
            + $this->stok_b_plus + $this->stok_b_minus
            + $this->stok_ab_plus + $this->stok_ab_minus
            + $this->stok_o_plus + $this->stok_o_minus;
    }
}