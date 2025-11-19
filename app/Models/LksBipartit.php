<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LksBipartit extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'assigned_to',
        'created_by',
        'notes'
    ];

    const STATUSES = [
        'review' => 'Review',
        'on_progress' => 'On Progress',
        'done' => 'Done'
    ];

    const PRIORITIES = [
        1 => 'Low',
        2 => 'Medium',
        3 => 'High',
        4 => 'Urgent'
    ];

    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getPriorityNameAttribute()
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    public function getFormattedDueDateAttribute()
    {
        return $this->due_date ? \Carbon\Carbon::parse($this->due_date)->format('d M Y') : '-';
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isOverdue()
    {
        if (!$this->due_date) return false;
        return \Carbon\Carbon::parse($this->due_date)->isPast() && $this->status != 'done';
    }

    public function getStatusName($status)
    {
        return self::STATUSES[$status] ?? $status;
    }
}
