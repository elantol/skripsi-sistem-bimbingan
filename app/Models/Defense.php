<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Defense extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'supervision_id',
        'defense_type',
        'title',
        'abstract',
        'status',
        'scheduled_date',
        'start_time',
        'end_time',
        'method',
        'room',
        'meeting_url',
        'final_score',
        'grade',
        'notes',
        'submitted_at',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'final_score' => 'float',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function supervision(): BelongsTo
    {
        return $this->belongsTo(Supervision::class);
    }

    public function examiners(): HasMany
    {
        return $this->hasMany(DefenseExaminer::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(DefenseScore::class);
    }
}
