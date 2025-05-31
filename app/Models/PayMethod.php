<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayMethod extends Model
{
    //
    protected $table = "pay_methods";
    protected $fillable = ["name", "coin", "abr", "factor", "status", "user_name", "user_phone", "user_dni", "user_account", "user_bank"];

}
