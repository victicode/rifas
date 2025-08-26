<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ["number", "rifa_id", "order_id", "status"];

    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class, "rifa_id");
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, "order_id");
    }
    public function winner(): HasOne
    {
        return $this->hasOne(Winner::class, "ticket_id");
    }
}
