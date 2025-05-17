<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,);
    }

    public function preOrder()
    {
        return $this->hasMany(PreOrder::class);
    }

    // menambahkan hitung otomatis pada final_stock
    protected static function booted()
    {
        static::creating(function ($product) {
            // Hitung nilai final_stock sebelum data disimpan
            $product->final_stock = $product->stock + $product->in_stock - $product->out_stock;
        });

        // menambah slug otomatis dengan nama
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name_product);
        });
    }

    // memanggil menggunakan slug untuk edit, view
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
