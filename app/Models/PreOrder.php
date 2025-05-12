<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * 
 *
 * @property int $id
 * @property string $code_po
 * @property int $product_id
 * @property int $user_id
 * @property int $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $users
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereCodePo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereUserId($value)
 * @mixin \Eloquent
 */
class PreOrder extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
 
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

        public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
        public function status()
    {
        return $this->belongsTo(Status::class);
    }

    protected static function booted()
    {
        static::created(function ($preOrder) {
            $product = Product::find($preOrder->product_id);
    
            if ($product) {
                $outStock = intval($preOrder->total); // jumlah preorder
                $product->out_stock += $outStock;
                // Hitung ulang final_stock
                $product->final_stock = $product->stock + $product->in_stock - $product->out_stock;
                $product->save();
            }
        });
    }
    

}
