<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penalty extends Model
{
    protected $fillable = [
        'operator_id',
        'meeting_id',
        'meeting_attendance_id',
        'amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'reason',
        'due_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function meetingAttendance(): BelongsTo
    {
        return $this->belongsTo(MeetingAttendance::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PenaltyPayment::class);
    }

    public function updatePaymentStatus()
    {
        $this->paid_amount = $this->payments()->sum('amount');
        $this->remaining_amount = $this->amount - $this->paid_amount;

        if ($this->remaining_amount <= 0) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } else {
            $this->status = 'unpaid';
        }

        $this->save();
    }
}
