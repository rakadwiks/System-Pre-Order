<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{

    protected $guarded = [];
    public $incrementing = false;

    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }

}
