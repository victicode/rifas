<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Winner extends Model
{
    use SoftDeletes;

    protected $fillable = ["rifa_id", "reward_id", "ticket_id", "winner_photo"];
    
    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class, "rifa_id");
    }
    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class, "reward_id");
    }
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, "ticket_id");
    }
}
