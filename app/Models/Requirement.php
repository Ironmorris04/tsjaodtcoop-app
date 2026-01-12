<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Requirement extends Model
{
    protected $fillable = [
        'type',
        'file_path',
        'issue_date',
        'expiry_date',
        'document_number',
        'notes',
        'uploaded_by'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date'
    ];

    /**
     * Get the user who uploaded this requirement
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get formatted type name
     */
    public function getFormattedTypeAttribute()
    {
        $types = [
            'cda_compliance'   => 'Certificate of Compliance (CDA)',
            'tax_exemption'   => 'Certificate of Tax Exemption',
            'bir_registration'=> 'Annual Registration with the BIR',
            'business_permit' => 'Business Permit (From the LGU)'
        ];

        return $types[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }

    /**
     * Get the latest requirement for a specific type
     */
    public static function getLatestByType($type)
    {
        return self::where('type', $type)
            ->latest()
            ->first();
    }

    /**
     * ✅ S3 accessor: full URL to the file
     */
    public function getFileUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        return Storage::disk('s3')->url($this->file_path);
    }

    /**
     * (Optional) Check if file exists in S3
     */
    public function getFileExistsAttribute()
    {
        if (!$this->file_path) {
            return false;
        }

        return Storage::disk('s3')->exists($this->file_path);
    }

    /**
     * (Optional) Get filename only
     */
    public function getFileNameAttribute()
    {
        return $this->file_path
            ? basename($this->file_path)
            : null;
    }
}
