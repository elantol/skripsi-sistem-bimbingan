<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefenseExaminer extends Model
{
    use HasFactory;

    protected $fillable = [
        'defense_id',
        'lecturer_id',
        'role',
        'is_present',
    ];

    protected $casts = [
        'is_present' => 'boolean',
    ];

    public function defense(): BelongsTo
    {
        return $this->belongsTo(Defense::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }
}
