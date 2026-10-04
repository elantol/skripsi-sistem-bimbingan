<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'academic_period_id',
        'title',
        'description',
        'started_at',
        'status',
    ];

    protected $casts = [
        'started_at' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function guidanceRequests()
    {
        return $this->hasMany(GuidanceRequest::class);
    }

    public function progressRecords()
    {
        return $this->hasMany(ProgressRecord::class);
    }
}
