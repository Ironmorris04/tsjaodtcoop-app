<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'driver_id',
        'first_name',
        'last_name',
        'birthdate',
        'sex',
        'phone',
        'email',
        'address',
        'photo',
        'biodata_photo',
        'license_number',
        'license_type',
        'license_expiry',
        'license_photo',
        'license_restrictions',
        'dl_codes',
        'status',
        'hire_date',
        'emergency_contact',
        'approval_status',
        'approved_at',
        'approved_by',
        'rejection_reason'
    ];

    // THIS IS IMPORTANT - Add this if missing!
    protected $casts = [
        'license_expiry' => 'date',
        'birthdate' => 'date',
        'hire_date' => 'date',
        'approved_at' => 'datetime'
    ];

    // Append accessor attributes to JSON
    protected $appends = ['user_id', 'full_name'];

    // Relationship: Driver belongs to an Operator
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope: Get pending drivers
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    // Scope: Get approved drivers
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    // Scope: Get rejected drivers
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    // NEW: Get driver's license document specifically
    public function driversLicense()
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('type', 'drivers_license')
            ->latest();
    }

    // Helper: Get full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Relationship: Driver has one Unit
    public function unit()
    {
        return $this->hasOne(Unit::class, 'driver_id', 'id');
    }

    // Virtual attribute to provide user object for backward compatibility
    public function getUserAttribute()
    {
        return (object) ['user_id' => $this->driver_id];
    }

    // Accessor to return driver_id as user_id
    public function getUserIdAttribute()
    {
        return $this->driver_id;
    }

    // NEW: Check if license is expiring soon
    public function isLicenseExpiringSoon($days = 30)
    {
        if (!$this->license_expiry) {
            return false;
        }

        return $this->license_expiry->isFuture() && 
               $this->license_expiry->diffInDays(now()) <= $days;
    }

    // NEW: Check if license is expired
    public function isLicenseExpired()
    {
        if (!$this->license_expiry) {
            return false;
        }

        return $this->license_expiry->isPast();
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::disk('s3')->url($this->photo) : null;
    }

    public function getBiodataPhotoUrlAttribute()
    {
        return $this->biodata_photo ? Storage::disk('s3')->url($this->biodata_photo) : null;
    }

    public function getLicensePhotoUrlAttribute()
    {
        return $this->license_photo ? Storage::disk('s3')->url($this->license_photo) : null;
    }

    /**
     * Boot method to log activities
     */
    protected static function boot()
    {
        parent::boot();

        // Log activity when driver is created
        static::created(function ($driver) {
            Activity::log(
                'driver_added',
                $driver->full_name . ' was added as a driver',
                null,
                $driver
            );
        });
    }
}