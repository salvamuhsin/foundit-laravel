<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'from_id', 'to_id', 'body'];

    public function item(): BelongsTo { return $this->belongsTo(Item::class); }
    public function sender(): BelongsTo { return $this->belongsTo(User::class, 'from_id'); }
    public function recipient(): BelongsTo { return $this->belongsTo(User::class, 'to_id'); }
}
