<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPay extends Model
{
    //
    protected $table = "data_pays";
    protected $fillable = ["title", "value", "method_id"];
}
