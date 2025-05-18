<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class,);
    }
    public function province()
    {
        return $this->belongsTo(Provinces::class, 'province_id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    // membuat default ketika input
    protected static function booted()
    {
        static::creating(function ($supplier) {
            $supplier->slug = Str::slug($supplier->name_supplier);
        });
    }
}
