<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    //
    protected $fillable = ["amount", "quantity", "reference", "vaucher", "pay_date", "status", "client_id", "rifa_id"];

    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class, "rifa_id");
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(client::class, "client_id");
    }
}
