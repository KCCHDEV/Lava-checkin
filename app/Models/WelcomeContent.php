<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelcomeContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'content',
        'type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get content by section
     */
    public static function getContent($section, $default = '')
    {
        $content = static::where('section', $section)
            ->where('is_active', true)
            ->first();
        
        return $content ? $content->content : $default;
    }

    /**
     * Get all active contents as array
     */
    public static function getAllContents()
    {
        return static::where('is_active', true)
            ->pluck('content', 'section')
            ->toArray();
    }
}
