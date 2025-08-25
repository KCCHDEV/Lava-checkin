<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'category', // news, academic, student-life, events, technology
        'status', // draft, published, archived
        'published_at',
        'author_id',
        'tags',
        'view_count',
        'is_featured',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return [
            'draft' => 'ร่าง',
            'published' => 'เผยแพร่',
            'archived' => 'เก็บถาวร'
        ][$this->status] ?? 'ไม่ระบุ';
    }

    public function getCategoryTextAttribute()
    {
        return [
            'news' => 'ข่าวสาร',
            'academic' => 'วิชาการ',
            'student-life' => 'ชีวิตนักเรียน',
            'events' => 'กิจกรรม',
            'technology' => 'เทคโนโลยี'
        ][$this->category] ?? 'ไม่ระบุ';
    }

    public function getCategoryColorAttribute()
    {
        return [
            'news' => 'primary',
            'academic' => 'success',
            'student-life' => 'info',
            'events' => 'warning',
            'technology' => 'secondary'
        ][$this->category] ?? 'secondary';
    }

    public function getShortContentAttribute()
    {
        return \Str::limit(strip_tags($this->content), 200);
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200); // Average reading speed
        return $minutes;
    }

    // Mutators
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = \Str::slug($value);
    }

    public function setExcerptAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['excerpt'] = \Str::limit(strip_tags($this->content), 300);
        } else {
            $this->attributes['excerpt'] = $value;
        }
    }

    // Methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function getTagsArrayAttribute()
    {
        return is_array($this->tags) ? $this->tags : [];
    }
}
