<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RifaConfiguration extends Model
{
    //
    use SoftDeletes;
    protected $table = "configuration_rifas";
    protected $fillable = ["quantity_tickets", "price", "minimus_buy", "auto_select", "banner_img", "all_img", "rifa_id", "create_by"];
    
    public function create_by(): BelongsTo
    {
        return $this->belongsTo(User::class, "create_by", "id");
    }
    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class, "rida_id");
    }

}
