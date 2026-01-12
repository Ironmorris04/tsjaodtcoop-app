<?php
// app/Models/Operator.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Operator extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_name',
        'contact_person',
        'gender',
        'phone',
        'email',
        'address',
        'business_permit_no',
        'status',
        'approval_status',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'membership_form_path',
        'membership_form_preview_path'
    ];

    protected $appends = [
        'membership_form_url',
        'membership_form_preview_url',
    ];

    /**
     * Relation to Detail (for Indigenous People, PWD, Senior, etc.)
     */
    public function detail()
    {
        return $this->hasOne(OperatorDetail::class); // <- Make sure the model name is correct
    }

    // URL for original uploaded membership form (PDF or image)
    public function getMembershipFormUrlAttribute()
    {
        return $this->membership_form_path ? Storage::disk('s3')->url($this->membership_form_path) : null;
    }

    // URL for converted PNG preview (first page of PDF)
    public function getMembershipFormPreviewUrlAttribute()
    {
        return $this->membership_form_preview_path ? Storage::disk('s3')->url($this->membership_form_preview_path) : null;
    }


    // Relationship: Operator belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function meetingAttendances()
    {
        return $this->hasMany(MeetingAttendance::class);
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'meeting_attendances')
            ->withPivot('status', 'remarks', 'checked_in_at')
            ->withTimestamps();
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    public function penaltyPayments()
    {
        return $this->hasMany(PenaltyPayment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function operatorDetail()
    {
        return $this->hasOne(OperatorDetail::class);
    }

    public function details()
    {
        return $this->hasOne(OperatorDetail::class);
    }

    public function officers()
    {
        return $this->hasMany(Officer::class);
    }

    public function getFullNameAttribute()
    {
        if ($this->operatorDetail) {
            $parts = array_filter([
                $this->operatorDetail->first_name,
                $this->operatorDetail->middle_name,
                $this->operatorDetail->last_name
            ]);
            if (!empty($parts)) {
                return implode(' ', $parts);
            }
        }

        return $this->contact_person ?? ($this->user->name ?? 'Unknown');
    }

    public function dependents()
    {
        return $this->hasMany(OperatorDependent::class);
    }

    public function operatorIds()
    {
        return $this->hasMany(OperatorId::class);
    }

    public function getTotalUnpaidPenaltiesAttribute()
    {
        return $this->penalties()
            ->whereIn('status', ['unpaid', 'partial'])
            ->sum('remaining_amount');
    }

    public function getTotalPaidPenaltiesAttribute()
    {
        return $this->penalties()->sum('paid_amount');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function businessPermit()
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('type', 'business_permit')
            ->latest();
    }

    public function franchise()
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('type', 'franchise')
            ->latest();
    }

    public function totalAbsences()
    {
        return $this->meetingAttendances()->where('status', 'absent')->count();
    }

    public function totalFineOwed()
    {
        return $this->totalAbsences() * 100;
    }

    public function totalFinePaid()
    {
        return $this->transactions()
            ->where('particular', 'fine')
            ->sum('amount');
    }

    public function remainingFineBalance()
    {
        return $this->totalFineOwed() - $this->totalFinePaid();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($operator) {
            Activity::log(
                'operator_registered',
                ($operator->business_name ?? 'New operator') . ' registered as a new operator',
                $operator->user_id ?? null,
                $operator
            );
        });
    }

    public function getAllExpiringDocuments($days = 30)
    {
        $documents = collect();

        $documents = $documents->merge(
            $this->documents()
                ->whereBetween('expiry_date', [now(), now()->addDays($days)])
                ->whereNotIn('status', ['expired', 'renewed'])
                ->get()
        );

        foreach ($this->drivers as $driver) {
            $documents = $documents->merge(
                $driver->documents()
                    ->whereBetween('expiry_date', [now(), now()->addDays($days)])
                    ->whereNotIn('status', ['expired', 'renewed'])
                    ->get()
            );
        }

        foreach ($this->units as $unit) {
            $documents = $documents->merge(
                $unit->documents()
                    ->whereBetween('expiry_date', [now(), now()->addDays($days)])
                    ->whereNotIn('status', ['expired', 'renewed'])
                    ->get()
            );
        }

        return $documents->sortBy('expiry_date');
    }


    protected static function booted()
    {
        static::forceDeleted(function ($operator) {

            if (!Auth::check() || !Auth::user()->isAdmin()) {
                throw new \Exception('Unauthorized delete attempt.');
            }

            /*
            |--------------------------------------------------------------------------
            | FILE CLEANUP
            |--------------------------------------------------------------------------
            */

            if ($operator->membership_form_path) {
                Storage::disk('public')->delete($operator->membership_form_path);
            }

            if ($operator->membership_form_preview_path) {
                Storage::disk('public')->delete($operator->membership_form_preview_path);
            }

            /*
            |--------------------------------------------------------------------------
            | CASCADE FORCE DELETES
            |--------------------------------------------------------------------------
            */

            $operator->meetings()->detach();
            $operator->meetingAttendances()->forceDelete();

            $operator->drivers()->each(function ($driver) {
                $driver->documents()->forceDelete();
                $driver->forceDelete();
            });

            $operator->units()->each(function ($unit) {
                $unit->documents()->forceDelete();
                $unit->forceDelete();
            });

            $operator->penaltyPayments()->forceDelete();
            $operator->penalties()->forceDelete();
            $operator->transactions()->forceDelete();

            $operator->officers()->forceDelete();
            $operator->dependents()->forceDelete();
            $operator->operatorIds()->forceDelete();

            $operator->documents()->forceDelete();

            $operator->detail()?->forceDelete();
            $operator->operatorDetail()?->forceDelete();

            if ($operator->user) {
                $operator->user->forceDelete();
            }
        });
    }

}
