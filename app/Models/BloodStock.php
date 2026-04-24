<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BloodStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_name',
        'location',
        'address',
        'phone',
        'blood_type',
        'rhesus',
        'stock_bags',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'date',
        'stock_bags'   => 'integer',
    ];
}