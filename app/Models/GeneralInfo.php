<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralInfo extends Model
{
    protected $table = 'general_info';

    protected $fillable = [
        'registration_no',
        'cooperative_name',
        'reg_region',
        'reg_province',
        'reg_municipality_city',
        'reg_barangay',
        'reg_street',
        'reg_house_lot_blk_no',
        'present_region',
        'present_province',
        'present_municipality_city',
        'present_barangay',
        'present_street',
        'present_house_lot_blk_no',
        'date_registration_prior_ra9520',
        'date_registration_under_ra9520',
        'business_permit_no',
        'business_permit_date_issued',
        'business_permit_amount_paid',
        'tax_identification_number',
        'category_of_cooperative',
        'type_of_cooperative',
        'asset_size',
        'common_bond_membership',
        'date_of_general_assembly',
        'area_of_operation',
    ];

    protected $casts = [
        'date_registration_prior_ra9520' => 'date',
        'date_registration_under_ra9520' => 'date',
        'business_permit_date_issued' => 'date',
        'business_permit_amount_paid' => 'decimal:2',
        'date_of_general_assembly' => 'date',
    ];
}
