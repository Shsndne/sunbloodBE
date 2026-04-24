<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmergencyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'patient_age',
        'gender',
        'diagnosis',
        'blood_type',
        'rhesus',
        'bags_needed',
        'needed_date',
        'urgency',
        'hospital_name',
        'full_address',
        'contact_name',
        'contact_phone',
        'receipt_number',
        'status',
        'declaration_agreed',
    ];

    protected $casts = [
        'needed_date'        => 'date',
        'declaration_agreed' => 'boolean',
        'patient_age'        => 'integer',
        'bags_needed'        => 'integer',
    ];
}