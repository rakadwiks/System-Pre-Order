<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $guarded = [];
    public $incrementing = false;

    public function province()
    {
        return $this->belongsTo(Provinces::class);
    }
}
