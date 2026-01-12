<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    use HasFactory;

    protected $fillable = ['filename', 'status', 'notes', 'admin_id'];

    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }
}
