<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassificationDataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'guidance_count',
        'revision_count',
        'submission_count',
        'progress_percentage',
        'late_days',
        'guidance_frequency',
        'label',
        'dataset_period',
    ];

    protected $casts = [
        'progress_percentage' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
