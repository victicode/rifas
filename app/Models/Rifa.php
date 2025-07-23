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
    public $appends     = ["status_label", "soldTickets", "available_tickets"];
    
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
        $ticketsInStandBy = 0;
        foreach ($this->ordersPending as $key) {
           $ticketsInStandBy += $key->quantity;
        }
        
        $t = ( ($this->tickets_count + $ticketsInStandBy)/$this->configuration->quantity_tickets)*100;
        return round($t, 2);
    }
    public function getAvailableTicketsAttribute()
    {  
         $ticketsInStandBy = 0;
        foreach ($this->ordersPending as $key) {
           $ticketsInStandBy += $key->quantity;
        }

        return  $this->configuration->quantity_tickets - ($ticketsInStandBy + $this->tickets_count) ;
    }
    public function configuration() : HasOne {
        return $this->hasOne(RifaConfiguration::class, "rifa_id");
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function ordersPending(): HasMany
    {
        return $this->hasMany(Order::class)->where('status', 1);
    }
    public function rewards(): HasMany
    {
        return $this->hasMany(Reward::class);
    }
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
