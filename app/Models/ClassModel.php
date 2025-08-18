<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassModel extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'code',
        'description',
        'start_time',
        'end_time',
        'days',
        'room',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'days' => 'array',
        'is_active' => 'boolean',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'class_student')
                    ->withPivot(['enrolled_at', 'dropped_at', 'is_active'])
                    ->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    public function getDaysStringAttribute(): string
    {
        return implode(', ', array_map('ucfirst', $this->days ?? []));
    }
}
