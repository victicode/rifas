<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    //
    protected $fillable = ["number", "rifa_id", "order_id", "status"];

     public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class, "rifa_id");
    }

     public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, "order_id");
    }
}
