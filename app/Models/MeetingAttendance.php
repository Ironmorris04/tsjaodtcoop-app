<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'operator_id',
        'status',
        'remarks',
        'checked_in_at'
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    // Relationship: Attendance belongs to a Meeting
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    // Relationship: Attendance belongs to an Operator
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    // Relationship: Attendance has one Penalty
    public function penalty()
    {
        return $this->hasOne(Penalty::class);
    }
}
