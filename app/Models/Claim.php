<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Claim extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'claimant_id', 'answer', 'details', 'status', 'decline_reason'];

    public function item(): BelongsTo { return $this->belongsTo(Item::class); }
    public function claimant(): BelongsTo { return $this->belongsTo(User::class, 'claimant_id'); }
    public function meetup(): HasOne { return $this->hasOne(Meetup::class); }
}
