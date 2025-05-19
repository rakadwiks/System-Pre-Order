<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PreOrder extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }


    protected static function booted()
    {
        static::created(function ($preOrder) {
            $product = Product::find($preOrder->product_id);

            if ($product) {
                // Anggap 'total' adalah jumlah produk yang dipesan
                $outStock = intval($preOrder->total); // jumlah preorder
                $product->out_stock += $outStock;

                // Hitung ulang final_stock
                $product->final_stock = $product->stock + $product->in_stock - $product->out_stock;

                $product->save();
            }
        });
    }
}
