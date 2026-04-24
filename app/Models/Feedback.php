<?php
// app/Models/Feedback.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'nama',
        'email',
        'pesan',
        'rating',
        'status',
        'admin_response',
        'responded_at',
    ];

    protected $casts = [
        'rating'       => 'integer',
        'responded_at' => 'datetime',
    ];
}