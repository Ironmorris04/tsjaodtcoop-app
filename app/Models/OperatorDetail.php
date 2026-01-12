<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OperatorDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'first_name',
        'middle_name',
        'last_name',
        'birthdate',
        'birthplace',
        'religion',
        'citizenship',
        'occupation',
        'sex',
        'civil_status',
        'indigenous_people',
        'pwd',
        'senior_citizen',
        'fourps_beneficiary',
        'id_type',
        'id_number',
        'valid_id_path',
        'profile_photo_path'
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];


    // ✅ ADD helper methods to check values
    public function isIndigenousPeople()
    {
        return strtolower($this->indigenous_people) === 'yes';
    }

    public function isPwd()
    {
        return strtolower($this->pwd) === 'yes';
    }

    public function isSeniorCitizen()
    {
        return strtolower($this->senior_citizen) === 'yes';
    }

    public function isFourpsBeneficiary()
    {
        return strtolower($this->fourps_beneficiary) === 'yes';
    }


    // Relationship: OperatorDetail belongs to an Operator
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    // Helper: Get full name
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    // Helper: Get age from birthdate
    public function getAgeAttribute()
    {
        if (!$this->birthdate) {
            return null;
        }
        return $this->birthdate->age;
    }

    // Helper: Get profile photo URL
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path ? Storage::disk('s3')->url($this->profile_photo_path) : null;
    }

    // Helper: Get valid ID URL
    public function getValidIdUrlAttribute()
    {
        return $this->valid_id_path ? Storage::disk('s3')->url($this->valid_id_path) : null;
    }

    // Helper: Get formatted ID type name
    public function getFormattedIdTypeAttribute()
    {
        if (!$this->id_type) {
            return null;
        }

        // Special cases that need specific formatting
        $specialCases = [
            'tinid' => 'TIN ID',
            'sss' => 'SSS ID',
            'philhealth' => 'PhilHealth ID',
            'prc' => 'PRC ID',
            'umid' => 'UMID',
            'drivers_license' => "Driver's License",
            'voters' => "Voter's ID",
            'postal' => 'Postal ID',
            'passport' => 'Passport',
            'national_id' => 'National ID'
        ];

        return $specialCases[$this->id_type] ?? ucwords(str_replace('_', ' ', $this->id_type));
    }
}
