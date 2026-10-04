<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuidanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'guidance_request_id',
        'lecturer_id',
        'scheduled_date',
        'start_time',
        'end_time',
        'method',
        'location',
        'meeting_url',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function guidanceRequest()
    {
        return $this->belongsTo(GuidanceRequest::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}
