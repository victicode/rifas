<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayMethod extends Model
{
    //
    protected $table = "pay_methods";
    protected $fillable = ["name", "coin_id", "min_buy", "status"];
    public $appends     = ["status_label"];
    
    public function getstatusLabelAttribute()
    {   
        $status = [
            "Inactivo",
            "Activo",
        ];
        return $status[$this->status];
    }
    public function dataPay(): HasMany
    {
        return $this->hasMany(DataPay::class, 'method_id')->where('status', 1);
    }
    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'coin_id');
    }
}
