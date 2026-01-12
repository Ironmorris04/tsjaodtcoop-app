<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'type',
        'document_number',
        'issue_date',
        'expiry_date',
        'due_date',
        'status',
        'file_path',
        'notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'due_date' => 'date',
    ];

    /**
     * Get the owning documentable model (Driver, Operator, or Unit)
     */
    public function documentable()
    {
        return $this->morphTo();
    }

    /**
     * Scope to get expiring documents
     */
    public function scopeExpiring($query, $days = 30)
    {
        return $query->whereBetween('expiry_date', [
            now(),
            now()->addDays($days)
        ])->whereNotIn('status', ['expired', 'renewed']);
    }

    /**
     * Scope to get expired documents
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now())
            ->where('status', '!=', 'renewed');
    }

    /**
     * Scope to get pending documents
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get documents due this month
     */
    public function scopeDueThisMonth($query)
    {
        return $query->whereBetween('due_date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->where('status', 'pending');
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expiry_date) {
            return null;
        }
        
        return now()->diffInDays($this->expiry_date, false);
    }

    /**
     * Check if document is expired
     */
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if document is expiring soon
     */
    public function isExpiringSoon($days = 30)
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return $this->expiry_date->isFuture() && 
               $this->expiry_date->diffInDays(now()) <= $days;
    }

    /**
     * Get formatted document type
     */
    public function getFormattedTypeAttribute()
    {
        $types = [
            'drivers_license' => "Driver's License",
            'license' => "Driver's License",
            'vehicle_registration' => 'Vehicle Registration',
            'registration' => 'Vehicle Registration',
            'or_cr' => 'OR/CR',
            'franchise' => 'Franchise',
            'business_permit' => 'Business Permit',
            'insurance' => 'Insurance',
            'ltms_portal' => 'LTMS Portal',
            'smoke_test' => 'Smoke Emission Test',
            'emission_test' => 'Emission Test',
        ];
        
        return $types[$this->type] ?? ucwords(str_replace('_', ' ', $this->type));
    }

    /**
     * Get owner name
     */
    public function getOwnerNameAttribute()
    {
        if (!$this->documentable) {
            return 'Unknown';
        }

        if ($this->documentable_type === 'App\Models\Driver') {
            return $this->documentable->full_name;
        }

        if ($this->documentable_type === 'App\Models\Operator') {
            return $this->documentable->business_name ?? 
                   ($this->documentable->user ? 
                       $this->documentable->user->name ?? 
                       $this->documentable->user->email 
                       : 'Unknown Operator');
        }

        if ($this->documentable_type === 'App\Models\Unit') {
            return 'Unit: ' . $this->documentable->plate_number;
        }

        return 'Unknown';
    }

    /**
     * Auto-update status based on expiry date and log activities
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($document) {
            // Auto-update status if expired
            if ($document->expiry_date && $document->expiry_date->isPast() && $document->status !== 'renewed') {
                $document->status = 'expired';
            }
        });

        // Log activity when document is created
        static::created(function ($document) {
            Activity::log(
                'document_uploaded',
                $document->formatted_type . ' document was uploaded for ' . $document->owner_name,
                null,
                $document
            );
        });

        // Log activity when document is updated
        static::updated(function ($document) {
            // Only log if it's an actual update, not just status change
            if ($document->wasChanged() && !$document->wasChanged('status')) {
                Activity::log(
                    'document_updated',
                    $document->formatted_type . ' document was updated for ' . $document->owner_name,
                    null,
                    $document
                );
            }
        });
    }
}