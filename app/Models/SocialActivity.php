<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use DateTimeInterface;


class SocialActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_type',
        'activity_name',
        'date_conducted',
        'participants_count',
        'amount_utilized',
        'fund_source',
        'photos',
        'created_by',
    ];

    protected $casts = [
        'date_conducted' => 'date',
        'amount_utilized' => 'decimal:2',
        'participants_count' => 'integer',
        'photos' => 'array',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     * This fixes the timezone issue by formatting dates as Y-m-d instead of ISO 8601
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    /**
     * Activity types
     */
    const TYPE_COOPERATIVE = 'cooperative';
    const TYPE_COMMUNITY = 'community';

    /**
     * Fund sources for cooperative activities
     */
    const FUND_CETF = 'CETF';
    const FUND_OPTIONAL = 'Optional Fund';
    const FUND_OUTRIGHT_EXPENSE = 'Outright Expense';

    /**
     * Fund sources for community activities
     */
    const FUND_CDF = 'CDF';

    /**
     * Get the user who created this activity
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope: Filter by activity type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_conducted', [$startDate, $endDate]);
    }

    /**
     * Scope: Filter by month and year
     */
    public function scopeByMonthYear($query, $month = null, $year = null)
    {
        if ($month) {
            $query->whereMonth('date_conducted', $month);
        }
        if ($year) {
            $query->whereYear('date_conducted', $year);
        }
        return $query;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount_utilized, 2);
    }

    /**
     * Get photo URLs
     */
    public function getPhotoUrlsAttribute()
    {
        if (!$this->photos || !is_array($this->photos)) {
            return [];
        }

        return array_map(function ($photo) {
            return Storage::disk('s3')->url($photo);
        }, $this->photos);
    }


    /**
     * Get photo URLs as base64 for PDF generation
     */
    public function getPhotoBase64Attribute()
    {
        if (!$this->photos || !is_array($this->photos)) {
            return [];
        }

        return array_map(function ($photo) {
            try {
                // Get file contents from S3
                $contents = Storage::disk('s3')->get($photo);
                
                // Get mime type
                $extension = pathinfo($photo, PATHINFO_EXTENSION);
                $mimeType = match(strtolower($extension)) {
                    'jpg', 'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    default => 'image/jpeg'
                };
                
                // Return base64 data URI
                return 'data:' . $mimeType . ';base64,' . base64_encode($contents);
            } catch (\Exception $e) {
                Log::error('Error loading photo for PDF: ' . $e->getMessage());
                return null;
            }
        }, $this->photos);
    }

}