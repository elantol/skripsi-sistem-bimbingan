<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nim',
        'name',
        'study_program',
        'generation',
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

    public function activeAssignments()
    {
        return $this->hasMany(SupervisionAssignment::class)->where('is_active', true);
    }

    public function supervisions()
    {
        return $this->hasMany(Supervision::class);
    }

    public function activeSupervision()
    {
        return $this->hasOne(Supervision::class)->where('status', 'aktif');
    }

    public function progressRecords()
    {
        return $this->hasMany(ProgressRecord::class);
    }

    public function classificationDatasets()
    {
        return $this->hasMany(ClassificationDataset::class);
    }

    public function classificationResults()
    {
        return $this->hasMany(ClassificationResult::class);
    }

    public function latestClassificationResult()
    {
        return $this->hasOne(ClassificationResult::class)->latestOfMany('calculated_at');
    }

    public function defenses()
    {
        return $this->hasMany(Defense::class);
    }
}
