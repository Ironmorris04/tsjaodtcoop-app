<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    /**
     * Format time difference in human readable format
     * Returns "X day(s) ago" or "X hour(s) ago" or "Less than an hour ago"
     *
     * @param Carbon $dateTime
     * @return string
     */
    public static function timeAgo($dateTime)
    {
        if (!$dateTime instanceof Carbon) {
            $dateTime = Carbon::parse($dateTime);
        }

        $now = now();
        $diffInDays = (int) $dateTime->diffInDays($now);
        $diffInHours = (int) $dateTime->diffInHours($now);

        if ($diffInDays >= 1) {
            return $diffInDays . ' ' . ($diffInDays == 1 ? 'day' : 'days') . ' ago';
        } elseif ($diffInHours >= 1) {
            return $diffInHours . ' ' . ($diffInHours == 1 ? 'hour' : 'hours') . ' ago';
        } else {
            return 'Less than an hour ago';
        }
    }

    /**
     * Format days remaining until a date
     * Returns "X day(s) left" or "Less than a day" or "Expired"
     *
     * @param Carbon $date
     * @return string
     */
    public static function daysRemaining($date)
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $now = now()->startOfDay();
        $targetDate = $date->copy()->startOfDay();
        $diffInDays = $now->diffInDays($targetDate, false);

        if ($diffInDays < 0) {
            return 'Expired';
        } elseif ($diffInDays == 0) {
            return 'Less than a day';
        } elseif ($diffInDays == 1) {
            return '1 day left';
        } else {
            return $diffInDays . ' days left';
        }
    }

    /**
     * Get the number of days remaining (as integer)
     *
     * @param Carbon $date
     * @return int
     */
    public static function daysRemainingCount($date)
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $now = now()->startOfDay();
        $targetDate = $date->copy()->startOfDay();

        return $now->diffInDays($targetDate, false);
    }
}
