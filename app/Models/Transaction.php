<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'operator_id',
        'type',
        'category',
        'date',
        'particular',
        'month',
        'or_number',
        'amount',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];

    /**
     * Get the operator for this transaction
     */
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * Get the user who created this transaction
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get formatted particular name
     */
    public function getFormattedParticularAttribute()
    {
        $particulars = [
            'subscription_capital' => 'CBU/Subscription Capital',
            'management_fee' => 'Management Fee',
            'membership_fee' => 'Membership Fee',
            'monthly_dues' => 'Monthly Dues',
            'business_permit' => 'Business Permit',
            'misc' => 'Miscellaneous'
        ];

        return $particulars[$this->particular] ?? ucwords(str_replace('_', ' ', $this->particular));
    }

    /**
     * Get the fixed amounts for each particular type
     */
    public static function getParticularAmounts()
    {
        return [
            'subscription_capital' => 500,
            'management_fee' => 500,
            'membership_fee' => 500,
            'monthly_dues' => 150,
            'business_permit' => 0, // Variable
            'misc' => 0 // Variable
        ];
    }
}
