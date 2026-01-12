<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'meeting_date',
        'meeting_time',
        'start_time',
        'end_time',
        'location',
        'address',
        'type',
        'status',
        'agenda',
        'minutes'
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'meeting_time' => 'datetime',
    ];

    // Relationship: Meeting has many attendances
    public function attendances()
    {
        return $this->hasMany(MeetingAttendance::class);
    }

    public function meetingAttendances()
    {
        return $this->hasMany(MeetingAttendance::class);
    }

    // Relationship: Meeting has many operators through attendances
    public function operators()
    {
        return $this->belongsToMany(Operator::class, 'meeting_attendances')
            ->withPivot('status', 'remarks', 'checked_in_at')
            ->withTimestamps();
    }

    // Get present operators
    public function presentOperators()
    {
        return $this->attendances()->where('status', 'present')->count();
    }

    // Get absent operators
    public function absentOperators()
    {
        return $this->attendances()->where('status', 'absent')->count();
    }
}
