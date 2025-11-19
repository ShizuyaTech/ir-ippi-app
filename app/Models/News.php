<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'content',
        'source',
        'source_url',
        'author',
        'image_url',
        'published_at',
        'category',
        'is_external',
        'is_active',
        'view_count'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_external' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Kategori berita khusus otomotif
    const CATEGORIES = [
        'automotive' => 'Otomotif',
        'car_news' => 'Berita Mobil',
        'motorcycle_news' => 'Berita Motor',
        'industry' => 'Industri Otomotif',
        'technology' => 'Teknologi Otomotif',
        'electric_vehicle' => 'Kendaraan Listrik',
        'review' => 'Review Kendaraan'
    ];

    // Sumber berita otomotif Indonesia
    const AUTOMOTIVE_SOURCES = [
        // 'otomotifnet' => 'Otomotif Net',
        'autocar' => 'Autocar Indonesia',
        // 'gridoto' => 'GridOto',
        // 'motorplus' => 'Motorplus',
        // 'viva_otomotif' => 'VIVA Otomotif'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeExternal($query)
    {
        return $query->where('is_external', true);
    }

    public function scopeAutomotive($query)
    {
        return $query->whereIn('category', array_keys(self::CATEGORIES));
    }

    public function scopeLatestNews($query)
    {
        return $query->where('published_at', '<=', now())
                    ->orderBy('published_at', 'desc');
    }

    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getShortSummaryAttribute()
    {
        return Str::limit($this->summary, 150);
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('d F Y H:i') : '-';
    }

    public function incrementViewCount()
    {
        $this->update(['view_count' => $this->view_count + 1]);
    }
}
