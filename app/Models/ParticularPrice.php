<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticularPrice extends Model
{
    protected $fillable = [
        'particular',
        'amount',
        'start_date',
        'end_date',
        'notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user who created this price setting
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
        ];

        return $particulars[$this->particular] ?? ucwords(str_replace('_', ' ', $this->particular));
    }

    /**
     * Get all particular types (excluding misc)
     */
    public static function getParticularTypes()
    {
        return [
            'subscription_capital' => 'CBU/Subscription Capital',
            'management_fee' => 'Management Fee',
            'membership_fee' => 'Membership Fee',
            'monthly_dues' => 'Monthly Dues',
            'business_permit' => 'Business Permit',
        ];
    }

    /**
     * Scope to get active prices
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get prices for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('start_date', '<=', $date)
                     ->where('end_date', '>=', $date);
    }

    /**
     * Calculate total amount for a particular over a month range
     *
     * @param string $particular The particular type
     * @param string $fromMonth Month name (e.g., "January")
     * @param string $toMonth Month name (e.g., "March")
     * @param int $year Year
     * @return array ['amount' => float, 'price_per_month' => float, 'months' => int]
     */
    public static function calculateAmount($particular, $fromMonth, $toMonth, $year)
    {
        // Convert month names to numbers
        $monthMap = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];

        $fromMonthNum = $monthMap[$fromMonth] ?? 1;
        $toMonthNum = $monthMap[$toMonth] ?? 1;

        // Calculate number of months
        if ($toMonthNum >= $fromMonthNum) {
            // Same year or normal range
            $numberOfMonths = $toMonthNum - $fromMonthNum + 1;
        } else {
            $numberOfMonths = (12 - $fromMonthNum) + $toMonthNum + 1;
        }

        // Get price for the middle of the range
        $middleDate = \Carbon\Carbon::createFromDate($year, $fromMonthNum, 15);

        $price = self::active()
            ->where('particular', $particular)
            ->forDate($middleDate)
            ->first();

        if (!$price) {
            // Fall back to default prices if no price setting found
            $defaultPrices = [
                'subscription_capital' => 500,
                'management_fee' => 500,
                'membership_fee' => 500,
                'monthly_dues' => 150,
                'business_permit' => 0,
            ];

            $pricePerMonth = $defaultPrices[$particular] ?? 0;
        } else {
            $pricePerMonth = (float) $price->amount;
        }

        $totalAmount = $pricePerMonth * $numberOfMonths;

        return [
            'amount' => $totalAmount,
            'price_per_month' => $pricePerMonth,
            'months' => $numberOfMonths,
            'price_id' => $price->id ?? null
        ];
    }

    /**
     * Get price for a particular on a specific date
     *
     * @param string $particular The particular type
     * @param string|\Carbon\Carbon $date The date
     * @return float|null
     */
    public static function getPriceForDate($particular, $date)
    {
        $price = self::active()
            ->where('particular', $particular)
            ->forDate($date)
            ->first();

        if ($price) {
            return (float) $price->amount;
        }

        // Fall back to default prices
        $defaultPrices = [
            'subscription_capital' => 500,
            'management_fee' => 500,
            'membership_fee' => 500,
            'monthly_dues' => 150,
            'business_permit' => 0,
        ];

        return $defaultPrices[$particular] ?? null;
    }
}
