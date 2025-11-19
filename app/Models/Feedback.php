<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'priority',
        'status',
        'management_response',
        'submitted_at',
        'responded_at',
        'created_by',
        'responded_by'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    const CATEGORIES = [
        'kebijakan' => 'Kebijakan Perusahaan',
        'kondisi_kerja' => 'Kondisi Kerja',
        'fasilitas' => 'Fasilitas',
        'komunikasi' => 'Komunikasi',
        'lainnya' => 'Lainnya'
    ];

    const PRIORITIES = [
        'low' => 'Rendah',
        'medium' => 'Sedang',
        'high' => 'Tinggi',
        'urgent' => 'Mendesak'
    ];

    const STATUSES = [
        'draft' => 'Draft',
        'submitted' => 'Terkirim',
        'under_review' => 'Dalam Review',
        'responded' => 'Ditanggapi',
        'closed' => 'Ditutup'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    // Method untuk cek akses tanpa dependency role
    public function canBeEditedBy($user)
    {
        return $this->created_by === $user->id && $this->status === 'draft';
    }

    public function canBeDeletedBy($user)
    {
        return $this->created_by === $user->id && $this->status === 'draft';
    }

    public function canBeSubmittedBy($user)
    {
        return $this->created_by === $user->id && $this->status === 'draft';
    }

    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getPriorityNameAttribute()
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
