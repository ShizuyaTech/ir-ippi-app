<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'ir_partnership',
        'conductive_working_climate', 
        'ess',
        'airsi',
        'updated_by'
    ];

    protected $casts = [
        'ir_partnership' => 'decimal:2',
        'conductive_working_climate' => 'decimal:2',
        'ess' => 'decimal:2',
        'airsi' => 'decimal:2',
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Method untuk mendapatkan score terbaru
    public static function getLatestScores()
    {
        return self::latest()->first();
    }

    // Method untuk mendapatkan progress bar color berdasarkan score
    public function getProgressColor($score)
    {
        if ($score >= 80) return 'success';
        if ($score >= 60) return 'info';
        if ($score >= 40) return 'warning';
        return 'danger';
    }

    // Method untuk membuat default scores jika tidak ada data
    public static function getDefaultScores()
    {
        return new self([
            'ir_partnership' => 0,
            'conductive_working_climate' => 0,
            'ess' => 0,
            'airsi' => 0,
        ]);
    }
}
