<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nidn',
        'name',
        'academic_degree',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisionAssignments()
    {
        return $this->hasMany(SupervisionAssignment::class);
    }

    public function guidanceRequests()
    {
        return $this->hasMany(GuidanceRequest::class);
    }

    public function guidanceSchedules()
    {
        return $this->hasMany(GuidanceSchedule::class);
    }

    public function guidanceNotes()
    {
        return $this->hasMany(GuidanceNote::class);
    }
}
