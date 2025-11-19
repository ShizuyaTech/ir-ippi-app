<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'type',
        'priority',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Tipe kegiatan yang tersedia
    const TYPES = [
        'meeting' => 'Meeting',
        'training' => 'Training',
        'event' => 'Event',
        'deadline' => 'Deadline',
        'other' => 'Lainnya'
    ];

    // Prioritas yang tersedia
    const PRIORITIES = [
        'low' => 'Rendah',
        'medium' => 'Sedang',
        'high' => 'Tinggi'
    ];

    // Status yang tersedia
    const STATUSES = [
        'scheduled' => 'Terjadwal',
        'in_progress' => 'Berlangsung',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope untuk kegiatan yang akan datang
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->format('Y-m-d'))
                    ->where('status', 'scheduled')
                    ->orderBy('start_date')
                    ->orderBy('start_time');
    }

    // Scope untuk kegiatan hari ini
    public function scopeToday($query)
    {
        return $query->where('start_date', now()->format('Y-m-d'))
                    ->where('status', 'scheduled')
                    ->orderBy('start_time');
    }

    // Cek apakah kegiatan sudah lewat
    public function getIsPastAttribute()
    {
        return $this->end_date < now()->format('Y-m-d');
    }

    // Cek apakah kegiatan berlangsung hari ini
    public function getIsTodayAttribute()
    {
        return $this->start_date == now()->format('Y-m-d');
    }

    // Getter untuk nama tipe
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    // Getter untuk nama prioritas
    public function getPriorityNameAttribute()
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    // Getter untuk nama status
    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    // Format tanggal dan waktu
    public function getFormattedStartDateTimeAttribute()
    {
        $date = $this->start_date->format('d F Y');
        $time = $this->start_time ? $this->start_time->format('H:i') : '';
        return $time ? "$date, $time" : $date;
    }

    public function getFormattedEndDateTimeAttribute()
    {
        $date = $this->end_date->format('d F Y');
        $time = $this->end_time ? $this->end_time->format('H:i') : '';
        return $time ? "$date, $time" : $date;
    }
}
