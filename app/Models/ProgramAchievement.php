<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgramAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'type',
        'category',
        'year',
        'order',
        'is_active',
        'published_at',
        'created_by'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];
    
    // Tipe program
    const TYPES = [
        'program' => 'Program',
        'achievement' => 'Achievement'
    ];

    // Kategori program
    const CATEGORIES = [
        'safety' => 'Safety Program',
        'community' => 'Community Development',
        'training' => 'Technical Training',
        'environment' => 'Environment',
        'innovation' => 'Innovation',
        'education' => 'Education',
        'health' => 'Health & Safety',
        'technology' => 'Technology',
        'award' => 'Award & Recognition',
        'partnership' => 'Partnership'
    ];

    // Tahun yang tersedia
    public static function getYears()
    {
        $currentYear = (int)date('Y');
        return [
            $currentYear,
            $currentYear - 1,
            $currentYear - 2
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ==================== SCOPES ====================
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePrograms($query)
    {
        return $query->where('type', 'program');
    }

    public function scopeAchievements($query)
    {
        return $query->where('type', 'achievement');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeByYear($query, $year = null)
    {
        if ($year && $year !== 'all') {
            return $query->where('year', $year);
        }
        return $query;
    }

    public function scopeByType($query, $type = null)
    {
        if ($type && in_array($type, ['program', 'achievement'])) {
            return $query->where('type', $type);
        }
        return $query;
    }

    public function scopeLatestPrograms($query)
    {
        return $query->orderBy('year', 'desc')
                    ->orderBy('order')
                    ->orderBy('published_at', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    // ==================== ATTRIBUTES ====================
    public function getShortDescriptionAttribute()
    {
        return Str::limit(strip_tags($this->description), 100);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return $this->getPlaceholderImage();
        }

        // Check if image starts with http:// or https://
        if (Str::startsWith(strtolower($this->image), ['http://', 'https://'])) {
            return $this->image;
        }

        // For storage files
        $path = 'images/' . ($this->type === 'program' ? 'programs/' : 'achievements/') . basename($this->image);
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return $this->getPlaceholderImage();
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->image_url;
    }

    protected function getPlaceholderImage()
    {
        $placeholderPath = 'images/' . ($this->type === 'program' ? 'programs/' : 'achievements/') . 'placeholder.jpg';

        if (!Storage::disk('public')->exists($placeholderPath)) {
            $defaultImage = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/4QBmRXhpZgAATU0AKgAAAAgABAEaAAUAAAABAAAAPgEbAAUAAAABAAAARgEoAAMAAAABAAIAAAExAAIAAAAQAAAATgAAAAAAAABgAAAAAQAAAGAAAAABcGFpbnQubmV0IDUuMC4xAP/bAEMABgQFBgUEBgYFBgcHBggKEAoKCQkKFA4PDBAXFBgYFxQWFhodJR8aGyMcFhYgLCAjJicpKikZHy0wLSgwJSgpKP/bAEMBBwcHCggKEwoKEygaFhooKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKP/AABEIAIAAgAMBIgACEQEDEQH/xAAfAAABBQEBAQEBAQAAAAAAAAAAAQIDBAUGBwgJCgv/xAC1EAACAQMDAgQDBQUEBAAAAX0BAgMABBEFEiExQQYTUWEHInEUMoGRoQgjQrHBFVLR8CQzYnKCCQoWFxgZGiUmJygpKjQ1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4eLj5OXm5+jp6vHy8/T19vf4+fr/xAAfAQADAQEBAQEBAQEBAAAAAAAAAQIDBAUGBwgJCgv/xAC1EQACAQIEBAMEBwUEBAABAncAAQIDEQQFITEGEkFRB2FxEyIygQgUQpGhscEJIzNS8BVictEKFiQ04SXxFxgZGiYnKCkqNTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqCg4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2dri4+Tl5ufo6ery8/T19vf4+fr/2gAMAwEAAhEDEQA/APD6KKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA//9k=');
            Storage::disk('public')->put($placeholderPath, $defaultImage);
        }
            
        return Storage::url($placeholderPath);
    }

    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('d F Y') : 'Draft';
    }

    public function getIsPublishedAttribute()
    {
        return $this->published_at && $this->published_at <= now();
    }

    // ==================== METHODS ====================
    public function isPublished()
    {
        return $this->published_at && $this->published_at <= now();
    }

    public static function getAvailableYears($type = null)
    {
        $query = self::active()->published();
        
        if ($type) {
            $query->where('type', $type);
        }
        
        return $query->select('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                    ->filter()
                    ->toArray();
    }

    public static function getYearRange()
    {
        $years = self::getAvailableYears();
        return !empty($years) ? $years : [date('Y')];
    }
}
