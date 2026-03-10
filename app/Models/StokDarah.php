<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokDarah extends Model
{
    use HasFactory, SoftDeletes;

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
        'stok_a_plus' => 'integer',
        'stok_a_minus' => 'integer',
        'stok_b_plus' => 'integer',
        'stok_b_minus' => 'integer',
        'stok_ab_plus' => 'integer',
        'stok_ab_minus' => 'integer',
        'stok_o_plus' => 'integer',
        'stok_o_minus' => 'integer',
    ];

    /**
     * Get total stok semua golongan
     */
    public function getTotalStokAttribute()
    {
        return $this->stok_a_plus + $this->stok_a_minus + 
               $this->stok_b_plus + $this->stok_b_minus + 
               $this->stok_ab_plus + $this->stok_ab_minus + 
               $this->stok_o_plus + $this->stok_o_minus;
    }

    /**
     * Get total stok golongan A
     */
    public function getTotalAAttribute()
    {
        return $this->stok_a_plus + $this->stok_a_minus;
    }

    /**
     * Get total stok golongan B
     */
    public function getTotalBAttribute()
    {
        return $this->stok_b_plus + $this->stok_b_minus;
    }

    /**
     * Get total stok golongan AB
     */
    public function getTotalABAttribute()
    {
        return $this->stok_ab_plus + $this->stok_ab_minus;
    }

    /**
     * Get total stok golongan O
     */
    public function getTotalOAttribute()
    {
        return $this->stok_o_plus + $this->stok_o_minus;
    }

    /**
     * Get status stok berdasarkan total
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
     * Get status color untuk badge
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

    /**
     * Scope untuk filter stok kritis
     */
    public function scopeKritis($query)
    {
        return $query->whereRaw('(stok_a_plus + stok_a_minus + stok_b_plus + stok_b_minus + stok_ab_plus + stok_ab_minus + stok_o_plus + stok_o_minus) < 30');
    }

    /**
     * Scope untuk filter berdasarkan golongan darah
     */
    public function scopeByGolongan($query, $golongan)
    {
        switch ($golongan) {
            case 'A':
                return $query->whereRaw('(stok_a_plus + stok_a_minus) > 0');
            case 'B':
                return $query->whereRaw('(stok_b_plus + stok_b_minus) > 0');
            case 'AB':
                return $query->whereRaw('(stok_ab_plus + stok_ab_minus) > 0');
            case 'O':
                return $query->whereRaw('(stok_o_plus + stok_o_minus) > 0');
            default:
                return $query;
        }
    }
}