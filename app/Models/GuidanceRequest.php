<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuidanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervision_id',
        'lecturer_id',
        'request_type',
        'title',
        'description',
        'status',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function supervision()
    {
        return $this->belongsTo(Supervision::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function latestDocument()
    {
        return $this->hasOne(Document::class)->latestOfMany();
    }

    public function guidanceSchedules()
    {
        return $this->hasMany(GuidanceSchedule::class);
    }

    public function latestSchedule()
    {
        return $this->hasOne(GuidanceSchedule::class)->latestOfMany();
    }

    public function guidanceNotes()
    {
        return $this->hasMany(GuidanceNote::class);
    }

    public function latestNote()
    {
        return $this->hasOne(GuidanceNote::class)->latestOfMany();
    }
}
