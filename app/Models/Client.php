<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ["ci", "name", "email", "phone", "city", "state"];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, "user_id");
    }
}
