<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Notification extends Model
{
    //
    protected $fillable = ["title", "content", "type", "is_read", "rifa_id", "order_id"];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, "order_id");
    }
    
    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class, "rifa_id");
    }
}
