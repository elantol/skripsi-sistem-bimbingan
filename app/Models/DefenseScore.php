<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefenseScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'defense_id',
        'lecturer_id',
        'presentation_score',
        'manuscript_score',
        'mastery_score',
        'product_score',
        'total_score',
        'revision_notes',
    ];

    protected $casts = [
        'presentation_score' => 'float',
        'manuscript_score' => 'float',
        'mastery_score' => 'float',
        'product_score' => 'float',
        'total_score' => 'float',
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
