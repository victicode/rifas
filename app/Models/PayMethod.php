<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayMethod extends Model
{
    //
    protected $table = "pay_methods";
    protected $fillable = ["name", "coin_id", "abr", "factor", "status"];
   
    public function dataPay(): HasMany
    {
        return $this->hasMany(DataPay::class, 'method_id');
    }
    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'coin_id');
    }
}
