<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'guidance_request_id',
        'uploaded_by',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function guidanceRequest()
    {
        return $this->belongsTo(GuidanceRequest::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function documentVersions()
    {
        return $this->hasMany(DocumentVersion::class)->orderBy('version_number', 'asc');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)->orderBy('version_number', 'asc');
    }

    public function latestVersion()
    {
        return $this->hasOne(DocumentVersion::class)->latestOfMany('version_number');
    }
}
