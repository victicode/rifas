<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    //
    protected $table = "system_configuration";
    protected $fillable = ["title", "value"];
}
