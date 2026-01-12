<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorDependent extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'name',
        'age',
        'relation'
    ];

    // Relationship: OperatorDependent belongs to an Operator
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
