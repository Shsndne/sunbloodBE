<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'feedback_text',
        'admin_response',
        'status',
        'responded_at'
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope untuk filter status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeResponded($query)
    {
        return $query->where('status', 'responded');
    }

    // Method untuk menandai sebagai dibaca
    public function markAsRead()
    {
        $this->status = 'read';
        $this->save();
    }

    // Method untuk memberikan response
    public function respond($response)
    {
        $this->admin_response = $response;
        $this->status = 'responded';
        $this->responded_at = now();
        $this->save();
    }
}