<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type', // general, important, event, academic
        'status', // draft, published, archived
        'published_at',
        'expires_at',
        'created_by',
        'image_path',
        'priority', // low, normal, high, urgent
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeImportant($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
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

    public function getTypeTextAttribute()
    {
        return [
            'general' => 'ทั่วไป',
            'important' => 'สำคัญ',
            'event' => 'กิจกรรม',
            'academic' => 'วิชาการ'
        ][$this->type] ?? 'ไม่ระบุ';
    }

    public function getPriorityTextAttribute()
    {
        return [
            'low' => 'ต่ำ',
            'normal' => 'ปกติ',
            'high' => 'สูง',
            'urgent' => 'ด่วน'
        ][$this->priority] ?? 'ไม่ระบุ';
    }

    public function getPriorityColorAttribute()
    {
        return [
            'low' => 'info',
            'normal' => 'primary',
            'high' => 'warning',
            'urgent' => 'danger'
        ][$this->priority] ?? 'secondary';
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getShortContentAttribute()
    {
        return \Str::limit(strip_tags($this->content), 150);
    }
}
