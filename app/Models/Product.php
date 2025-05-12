<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
   
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, );
    }

    public function preOrder()
    {
        return $this->hasMany(PreOrder::class);
    }



}
