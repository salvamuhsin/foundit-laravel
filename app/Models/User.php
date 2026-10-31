<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory,Notifiable;

    // role, restricted and restriction_reason are NOT mass assignable on purpose.
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed', 'restricted' => 'boolean', 'email_verified_at' => 'datetime'];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Message shown when a restricted member tries to write anything, or null when allowed. */
    public function restrictionMessage(): ?string
    {
        return $this->restricted ? trim('Your account is restricted. '.$this->restriction_reason) : null;
    }

    /** Pending claims other people made on this user's items. */
    public function pendingReviewCount(): int
    {
        return $this->receivedClaims()->where('claims.status', 'pending')->count();
    }

    public function items(): HasMany { return $this->hasMany(Item::class, 'owner_id'); }
    public function claims(): HasMany { return $this->hasMany(Claim::class, 'claimant_id'); }
    public function sentMessages(): HasMany { return $this->hasMany(Message::class, 'from_id'); }
    public function activityLogs(): HasMany { return $this->hasMany(ActivityLog::class); }

    public function receivedClaims(): HasManyThrough
    {
        return $this->hasManyThrough(Claim::class, Item::class, 'owner_id', 'item_id');
    }
}
