<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'supervision_id',
        'guidance_count',
        'revision_count',
        'submission_count',
        'progress_percentage',
        'late_days',
        'recorded_at',
    ];

    protected $casts = [
        'progress_percentage' => 'float',
        'recorded_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function supervision()
    {
        return $this->belongsTo(Supervision::class);
    }
}
