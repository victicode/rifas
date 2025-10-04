<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reward extends Model
{
    //
    protected $fillable = ["title", "reward_time", "rifa_id", "type", "pole"];
    
    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class, "rifa_id");
    }
    public function winner(): HasOne
    {
        return $this->hasOne(Winner::class, "reward_id");
    }


}
