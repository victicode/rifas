<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rifa extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ["title", "description", "due_date", "status"];
    public $appends     = ["status_label"];
    
    public function getstatusLabelAttribute()
    {   
        $status = [
            "Finalizada",
            "Activa",
        ];
        return $status[$this->status];
    }
    public function configuration() : HasOne {
        return $this->hasOne(RifaConfiguration::class, "rifa_id");
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function rewards(): HasMany
    {
        return $this->hasMany(Reward::class);
    }
    public function Tickects(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
