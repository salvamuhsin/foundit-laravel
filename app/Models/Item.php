<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['owner_id', 'type', 'title', 'category', 'description', 'location', 'date', 'image', 'question'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function claims(): HasMany { return $this->hasMany(Claim::class); }

    /** open | pending | meetup | returned (same rules as statusOf in the React app). Eager load `claims`. */
    protected function status(): Attribute
    {
        return Attribute::get(function () {
            $s = $this->claims->pluck('status');

            return match (true) {
                $s->contains('returned') => 'returned',
                $s->contains('approved') => 'meetup',
                $s->contains('pending') => 'pending',
                default => 'open',
            };
        });
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? Storage::url($this->image) : null);
    }

    /** Filter by the computed status in SQL. */
    public function scopeWithStatus(Builder $q, ?string $status): Builder
    {
        return match ($status) {
            'returned' => $q->whereHas('claims', fn ($c) => $c->where('status', 'returned')),
            'meetup' => $q->whereHas('claims', fn ($c) => $c->where('status', 'approved'))
                ->whereDoesntHave('claims', fn ($c) => $c->where('status', 'returned')),
            'pending' => $q->whereHas('claims', fn ($c) => $c->where('status', 'pending'))
                ->whereDoesntHave('claims', fn ($c) => $c->whereIn('status', ['approved', 'returned'])),
            'open' => $q->whereDoesntHave('claims', fn ($c) => $c->whereIn('status', ['pending', 'approved', 'returned'])),
            default => $q,
        };
    }
}
