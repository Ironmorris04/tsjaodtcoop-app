<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $entity_identifier
 * @property string $entity_name
 * @property string $formatted_type
 * @property string $status_badge_class
 * @property string|null $document_photo_url
 */
class DocumentRenewal extends Model
{
    protected $fillable = [
        'operator_id',
        'document_type',
        'documentable_type',
        'documentable_id',
        'original_expiry_date',
        'original_document_number',
        'new_expiry_date',
        'new_document_number',
        'document_photo',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'original_expiry_date' => 'date',
        'new_expiry_date' => 'date',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Relationship: Renewal belongs to an Operator
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documentable()
    {
        return $this->morphTo();
    }

    // Get formatted document type
    public function getFormattedTypeAttribute()
    {
        $types = [
            'driver_license' => "Driver's License",
            'business_permit' => 'Business Permit',
            'unit_or' => 'Unit OR (Official Receipt)',
            'unit_cr' => 'Unit CR (Certificate of Registration)',
            'lto_or' => 'LTO OR',
            'lto_cr' => 'LTO CR',
            'registration_expiry' => 'Vehicle Registration',
        ];

        return $types[$this->document_type] ?? ucwords(str_replace('_', ' ', $this->document_type));
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    // Get document photo URL
    public function getDocumentPhotoUrlAttribute()
    {
        return $this->document_photo ? Storage::url($this->document_photo) : null;
    }

    public function getEntityNameAttribute()
    {
        if ($this->documentable_type === 'App\Models\Driver' && $this->documentable) {
            return $this->documentable->full_name;
        } elseif ($this->documentable_type === 'App\Models\Unit' && $this->documentable) {
            return 'Unit: ' . $this->documentable->plate_no;
        }
        return 'Unknown';
    }

    // Get entity identifier (License Number for drivers, Plate Number for units)
    public function getEntityIdentifierAttribute()
    {
        if ($this->documentable_type === 'App\Models\Driver' && $this->documentable) {
            return $this->documentable->license_number ?? 'N/A';
        } elseif ($this->documentable_type === 'App\Models\Unit' && $this->documentable) {
            return $this->documentable->plate_no ?? 'N/A';
        }
        return 'N/A';
    }

    // Scope for pending renewals
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for approved renewals
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope for rejected renewals
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
