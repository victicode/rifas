<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    
    //
    use SoftDeletes;
    protected $fillable = ["amount", "quantity", "number", "reference", "vaucher", "pay_date", "status", "client_id", "rifa_id", "method_id"];

    public $appends     = ["status_label","status_color"];
    
    public function getstatusLabelAttribute()
    {   
        $status = [
            "Rechazada",
            "Pendiente",
            "Procesada",
        ];
        return $status[$this->status];
    }
     public function getstatusColorAttribute()
    {   
        $status = [
            "bg-negative",
            "bg-warning",
            "bg-positive",
        ];
        return $status[$this->status];
    }
    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class, "rifa_id");
    }
    public function methodPay(): BelongsTo
    {
        return $this->belongsTo(PayMethod::class, "method_id");
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, "client_id");
    }
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'order_id');
    }
}
