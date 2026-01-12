<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnualReport extends Model
{
    protected $fillable = [
        'report_year',
        'report_data',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'report_data' => 'array',
    ];

    /**
     * Get the user who created this report
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this report
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
