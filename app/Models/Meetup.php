<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meetup extends Model
{
    use HasFactory;
    protected $fillable = ['claim_id', 'location', 'datetime', 'note', 'status', 'proposed_by'];

    protected function casts(): array
    {
        return ['datetime' => 'datetime'];
    }

    public function claim(): BelongsTo { return $this->belongsTo(Claim::class); }
    public function proposer(): BelongsTo { return $this->belongsTo(User::class, 'proposed_by'); }

    /** Meetups where the user is the claimant or the person who posted the item. */
    public function scopeInvolving(Builder $q, User $user): Builder
    {
        return $q->whereHas('claim', fn ($c) => $c->where(
            fn ($w) => $w->where('claimant_id', $user->id)
                ->orWhereHas('item', fn ($i) => $i->where('owner_id', $user->id))
        ));
    }
}
