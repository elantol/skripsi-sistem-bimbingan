<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassificationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'model_version',
        'predicted_label',
        'confidence',
        'calculated_at',
    ];

    protected $casts = [
        'confidence' => 'float',
        'calculated_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
