<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayMethod extends Model
{
    //
    protected $table = "pay_methods";
    protected $fillable = ["name", "coin", "abr", "factor", "status", "user_name", "user_phone", "user_dni", "user_account", "user_bank"];
   
    public function dataPay(): HasMany
    {
        return $this->hasMany(DataPay::class, 'method_id');
    }
}
