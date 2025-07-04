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
    public $appends     = ["status_label", "soldTickets"];
    
    public function getstatusLabelAttribute()
    {   
        $status = [
            "Finalizada",
            "Activa",
        ];
        return $status[$this->status];
    }
    public function getSoldTicketsAttribute()
    {   
        $t = (count($this->tickects)/$this->configuration->quantity_tickets)*100;
        return round($t, 2);
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
    public function tickects(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
