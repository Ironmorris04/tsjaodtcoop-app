<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OperatorId extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'id_type',
        'id_number',
        'issue_date',
        'expiry_date',
        'issuing_authority',
        'id_image_path'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date'
    ];

    // Relationship: OperatorId belongs to an Operator
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    // Helper: Get ID image URL
    public function getIdImageUrlAttribute()
    {
        if ($this->id_image_path) {
            return Storage::disk('s3')->url($this->id_image_path);
        }
        return null;
    }


    // Helper: Check if ID is expired
    public function getIsExpiredAttribute()
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date->isPast();
    }

    public function getIsExpiringSoonAttribute()
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date->isBetween(now(), now()->addDays(30));
    }
}
