<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rifa extends Model
{
    //
    protected $fillable = ["title", "description", "due_date", "status"];

    public function configuration() : HasOne {
        return $this->hasOne(RifaConfiguration::class, "rifa_id");
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function Tickects(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
