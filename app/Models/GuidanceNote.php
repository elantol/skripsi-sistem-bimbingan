<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuidanceNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'guidance_request_id',
        'lecturer_id',
        'note',
        'result',
        'progress_percentage',
    ];

    protected $casts = [
        'progress_percentage' => 'float',
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
