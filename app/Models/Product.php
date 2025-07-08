<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name_product', 'price', 'stock', 'total_price', 'in_stock', 'out_stock', 'final_stock',]) // Field yang akan dilog
            ->useLogName('user')         // Nama log
            ->logOnlyDirty()             // Hanya jika field berubah
            ->setDescriptionForEvent(fn(string $eventName) => "User model has been {$eventName}");
    }

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

        // Menghitung total price dari stock * item
        static::creating(function ($product) {
            $product->total_price = $product->stock * $product->price;
        });
    }

    // memanggil menggunakan slug untuk edit, view
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
