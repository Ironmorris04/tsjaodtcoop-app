<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'user_id',
        'subject_type',
        'subject_id',
        'properties'
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    // Relationship: Activity belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    // Helper method to log an activity
    public static function log($type, $description, $userId = null, $subject = null, $properties = [])
    {
        return self::create([
            'type' => $type,
            'description' => $description,
            'user_id' => $userId ?? auth()->id(),
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'properties' => $properties,
        ]);
    }

    // Get formatted activity type
    public function getFormattedTypeAttribute()
    {
        $types = [
            'operator_registered' => 'Operator Registration',
            'driver_added' => 'Driver Added',
            'unit_added' => 'Vehicle Registered',
            'document_uploaded' => 'Document Uploaded',
            'meeting_created' => 'Meeting Created',
            'attendance_marked' => 'Attendance Marked',
            'document_updated' => 'Document Updated',
            'profile_updated' => 'Profile Updated',
            'operator_approved' => 'Operator Approved',
            'operator_rejected' => 'Operator Rejected',
            'driver_approved' => 'Driver Approved',
            'driver_rejected' => 'Driver Rejected',
            'unit_approved' => 'Unit Approved',
            'unit_rejected' => 'Unit Rejected',
        ];

        return $types[$this->type] ?? ucwords(str_replace('_', ' ', $this->type));
    }

    // Get icon class based on activity type
    public function getIconAttribute()
    {
        $icons = [
            'operator_registered' => 'fa-user-plus',
            'driver_added' => 'fa-id-card',
            'unit_added' => 'fa-bus',
            'document_uploaded' => 'fa-file-upload',
            'meeting_created' => 'fa-calendar-plus',
            'attendance_marked' => 'fa-check-circle',
            'document_updated' => 'fa-edit',
            'profile_updated' => 'fa-user-edit',
            'operator_approved' => 'fa-user-check',
            'operator_rejected' => 'fa-user-times',
            'driver_approved' => 'fa-id-card-alt',
            'driver_rejected' => 'fa-id-card',
            'unit_approved' => 'fa-check-square',
            'unit_rejected' => 'fa-times-circle',
        ];

        return $icons[$this->type] ?? 'fa-circle';
    }

    // Get color class based on activity type
    public function getColorAttribute()
    {
        $colors = [
            'operator_registered' => 'blue',
            'driver_added' => 'green',
            'unit_added' => 'orange',
            'document_uploaded' => 'purple',
            'meeting_created' => 'teal',
            'attendance_marked' => 'green',
            'document_updated' => 'yellow',
            'profile_updated' => 'blue',
            'operator_approved' => 'success',
            'operator_rejected' => 'danger',
            'driver_approved' => 'success',
            'driver_rejected' => 'danger',
            'unit_approved' => 'success',
            'unit_rejected' => 'danger',
        ];

        return $colors[$this->type] ?? 'blue';
    }
    
}
