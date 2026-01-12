<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'driver_id',
        'unit_id',
        'plate_no',
        'body_number',
        'engine_number',
        'chassis_number',
        // NEW
        'police_number',
        'coding_number',
        'color',
        'lto_cr_number',
        'lto_cr_date_issued',
        'lto_cr_validity',
        'lto_or_number',
        'lto_or_date_issued',
        'unit_cr_number',
        'unit_or_number',
        'franchise_case',
        'mv_file',
        'mbp_no_prev_year',
        'mch_no_prev_year',
        'year_model',
        'type',
        'brand',
        'model',
        'capacity',
        'status',
        'registration_expiry',
        'cr_receipt_photo',
        'cr_photo',
        'unit_photo',
        'business_permit_photo',
        'business_permit_no',
        'business_permit_validity',
        'or_photo',
        'or_number',
        'or_date_issued',
        'cr_number',
        'cr_validity',
        'approval_status',
        'approved_at',
        'approved_by',
        'rejection_reason'
    ];

    protected $casts = [
        'registration_expiry' => 'date',
        'lto_cr_date_issued' => 'date',
        'lto_cr_validity' => 'date',
        'lto_or_date_issued' => 'date',
        'business_permit_validity' => 'date',
        'or_date_issued' => 'date',
        'cr_validity' => 'date',
        'approved_at' => 'datetime'
    ];

    // Append accessor attributes to JSON
    protected $appends = ['user_id', 'plate_number'];

    public function getPlateNumberAttribute()
    {
        return $this->attributes['plate_no'] ?? null;
    }

    // Accessor to return unit_id as user_id
    public function getUserIdAttribute()
    {
        return $this->unit_id;
    }

    // Relationship: Unit belongs to an Operator
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope: Get pending units
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    // Scope: Get approved units
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    // Scope: Get rejected units
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    // NEW: Get vehicle registration document specifically
    public function vehicleRegistration()
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('type', 'vehicle_registration')
            ->latest();
    }

    // NEW: Get OR/CR document specifically
    public function orCr()
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('type', 'or_cr')
            ->latest();
    }

    // NEW: Get insurance document specifically
    public function insurance()
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('type', 'insurance')
            ->latest();
    }

    // NEW: Get franchise document specifically
    public function franchise()
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('type', 'franchise')
            ->latest();
    }

    // NEW: Check if registration is expiring soon
    public function isRegistrationExpiringSoon($days = 30)
    {
        if (!$this->registration_expiry) {
            return false;
        }

        return $this->registration_expiry->isFuture() && 
               $this->registration_expiry->diffInDays(now()) <= $days;
    }

    // NEW: Check if registration is expired
    public function isRegistrationExpired()
    {
        if (!$this->registration_expiry) {
            return false;
        }

        return $this->registration_expiry->isPast();
    }

    public function getExpiringItems($days = 30)
    {
        $expiring = collect();

        if ($this->isRegistrationExpiringSoon($days)) {
            $expiring->push([
                'type' => 'Vehicle Registration',
                'expiry_date' => $this->registration_expiry,
                'days_left' => now()->diffInDays($this->registration_expiry)
            ]);
        }

        return $expiring;
    }

    // Accessor for unit photo
    public function getUnitPhotoUrlAttribute()
    {
        return $this->unit_photo ? Storage::disk('s3')->url($this->unit_photo) : null;
    }

    public function getCrPhotoUrlAttribute()
    {
        return $this->cr_photo ? Storage::disk('s3')->url($this->cr_photo) : null;
    }

    public function getCrReceiptPhotoUrlAttribute()
    {
        return $this->cr_receipt_photo ? Storage::disk('s3')->url($this->cr_receipt_photo) : null;
    }

    public function getBusinessPermitPhotoUrlAttribute()
    {
        return $this->business_permit_photo ? Storage::disk('s3')->url($this->business_permit_photo) : null;
    }

    public function getOrPhotoUrlAttribute()
    {
        return $this->or_photo ? Storage::disk('s3')->url($this->or_photo) : null;
    }

    /**
     * Boot method to log activities
     */
    protected static function boot()
    {
        parent::boot();

        // Log activity when unit is created
        static::created(function ($unit) {
            Activity::log(
                'unit_added',
                'Vehicle ' . ($unit->plate_number ?? 'N/A') . ' was registered',
                null,
                $unit
            );
        });
    }
}