<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link_url',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = [
        'display_image_url',
    ];

    public function getDisplayImageUrlAttribute(): string
    {
        $url = (string) $this->image_url;

        if (empty($url)) {
            return '';
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        // Relative path in storage
        $cleaned = ltrim($url, '/');
        if (str_starts_with($cleaned, 'storage/')) {
            return url($cleaned);
        }

        return url('storage/' . $cleaned);
    }
}
