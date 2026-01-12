<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'user_id',
        'setup_token',
        'setup_token_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship: User has many Feedback entries
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function username()
    {
        return 'user_id';
    }

    // Relationship: User has one Operator profile
    public function operator()
    {
        return $this->hasOne(Operator::class);
    }

    // Helper methods for role checking


    public function faqRoute()
    {
        if ($this->isAdmin()) return route('admin.faqs');
        if ($this->isOperator()) return route('operator.faqs');
        if ($this->isTreasurer()) return route('treasurer.faqs');
        if ($this->isPresident()) return route('president.faqs');
        if ($this->isAuditor()) return route('auditor.faqs');

        return '#'; // fallback
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOperator()
    {
        return $this->role === 'operator';
    }

    public function isPresident()
    {
        return $this->role === 'president';
    }

    public function isTreasurer()
    {
        return $this->role === 'treasurer';
    }

    public function isAuditor()
    {
        return $this->role === 'auditor';
    }

    /**
     * Generate a unique user ID based on role, sequence number, and year
     * Format: {RolePrefix}{SequenceNumber}-{Year}
     * Example: O001-2025, A001-2025, T001-2025
     */
    public static function generateUserId($role)
    {
        $rolePrefixes = [
            'admin' => 'A',
            'operator' => 'O',
            'treasurer' => 'T',
            'auditor' => 'U',
            'president' => 'P',
        ];

        $prefix = $rolePrefixes[$role] ?? 'U';
        $year = date('Y');

        // Find the highest sequence number for this role and year
        $latestUserId = self::where('role', $role)
            ->whereNotNull('user_id')
            ->where('user_id', 'like', "{$prefix}%{$year}")
            ->orderByRaw('CAST(SUBSTRING(user_id, 2, 3) AS UNSIGNED) DESC')
            ->value('user_id');

        if ($latestUserId) {
            // Extract the sequence number from the latest user ID
            preg_match('/^[A-Z](\d+)-\d{4}$/', $latestUserId, $matches);
            $lastSequence = isset($matches[1]) ? (int)$matches[1] : 0;
            $sequenceNumber = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $sequenceNumber = '001';
        }

        $userId = "{$prefix}{$sequenceNumber}-{$year}";

        // Double-check uniqueness to handle race conditions
        $attempts = 0;
        while (self::where('user_id', $userId)->exists() && $attempts < 100) {
            $attempts++;
            $newSequence = (int)$sequenceNumber + $attempts;
            $sequenceNumber = str_pad($newSequence, 3, '0', STR_PAD_LEFT);
            $userId = "{$prefix}{$sequenceNumber}-{$year}";
        }

        return $userId;
    }

    /**
     * Generate a password setup token
     */
    public function generateSetupToken()
    {
        $this->setup_token = bin2hex(random_bytes(32));
        $this->setup_token_expires_at = now()->addDays(7); // Token expires in 7 days
        $this->save();

        return $this->setup_token;
    }
}