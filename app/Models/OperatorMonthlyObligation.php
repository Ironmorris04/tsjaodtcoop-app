<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorMonthlyObligation extends Model
{
    protected $fillable = [
        'operator_id',
        'month',
        'year',
        'amount'
    ];
}

