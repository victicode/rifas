<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPay extends Model
{
    //
    protected $table = "data_pays";
    protected $fillable = ["title", "value", "data", "method_id", "status"];
    public $appends     = ["status_label"];
    
    public function getstatusLabelAttribute()
    {   
        $status = [
            "Inactivo",
            "Activo",
        ];
        return $status[$this->status];
    }
    public function methodType(): BelongsTo
    {
        return $this->belongsTo(PayMethod::class, "method_id");
    }
}
