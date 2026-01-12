<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'operator_id',
        'operator_name',
        'action',
        'model',
        'model_id',
        'description',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Operator
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * Create an audit log entry
     */
    public static function log($action, $description, $model = null, $modelId = null, $changes = null)
    {
        $user = auth()->user();
        $operator = null;
        $operatorName = null;

        // Get operator information if user has an operator profile
        if ($user && $user->operator) {
            $operator = $user->operator;
            $operatorName = $operator->full_name;
        }

        return self::create([
            'user_id' => auth()->id(),
            'user_name' => $user?->name,
            'operator_id' => $operator?->id,
            'operator_name' => $operatorName,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'description' => $description,
            'changes' => $changes,
        ]);
    }
}
