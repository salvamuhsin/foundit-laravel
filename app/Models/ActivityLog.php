<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends Model
{
    use HasFactory;
    const UPDATED_AT = null;

    protected $fillable = ['user_id', 'action', 'description'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    /** One line in the admin activity log. Call it from every controller action that changes something. */
    public static function record(?User $user, string $action, string $description): void
    {
        static::create(['user_id' => $user?->id, 'action' => $action, 'description' => $description]);
    }
}
