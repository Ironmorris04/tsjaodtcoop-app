<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Officer extends Model
{
    protected $fillable = [
        'operator_id',
        'position',
        'committee',
        'effective_from',
        'effective_to',
        'is_active'
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Get the operator who holds this officer position
     */
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * Get formatted position name
     */
    public function getFormattedPositionAttribute()
    {
        $positions = [
            'chairperson' => 'Chairperson',
            'vice_chairperson' => 'Vice-Chairperson',
            'secretary' => 'Secretary',
            'treasurer' => 'Treasurer',
            'general_manager' => 'General Manager',
            'member' => 'Member',
            'bookkeeper' => 'Bookkeeper'
        ];

        return $positions[$this->position] ?? ucfirst(str_replace('_', ' ', $this->position));
    }

    /**
     * Check if officer term is currently active based on dates
     */
    public function isCurrentlyActive()
    {
        $today = Carbon::today();
        return $this->is_active
            && $this->effective_from <= $today
            && $this->effective_to >= $today;
    }

    /**
     * Scope to get active officers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get current officers (active and within date range)
     */
    public function scopeCurrent($query)
    {
        $today = Carbon::today();
        return $query->where('is_active', true)
            ->where('effective_from', '<=', $today)
            ->where('effective_to', '>=', $today);
    }
}
