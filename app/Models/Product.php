<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * 
 *
 * @property int $id
 * @property string $code_product
 * @property string $name_product
 * @property int $supplier_id
 * @property string $price
 * @property int $stock
 * @property int $in_stock
 * @property int $out_stock
 * @property int $final_stock
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PreOrder> $preOrder
 * @property-read int|null $pre_order_count
 * @property-read \App\Models\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCodeProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereFinalStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereInStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereNameProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOutStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    // menambahkan hitung otomatis pada final_stock
    protected static function booted()
        {
            static::creating(function ($product) {
                // Hitung nilai final_stock sebelum data disimpan
                $product->final_stock = $product->stock + $product->in_stock - $product->out_stock;
            });
        }

}
