<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PreOrder extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['total', 'status_name']) // Field yang akan dilog
            ->useLogName('user')         // Nama log
            ->logOnlyDirty()             // Hanya jika field berubah
            ->setDescriptionForEvent(fn(string $eventName) => "User model has been {$eventName}");
    }

    use HasFactory, Notifiable;
    protected $guarded = [];
    protected $casts = [
        'installed_date' => 'date', // atau 'datetime' jika pakai jam
    ];

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

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    public function status()
    {
        return $this->belongsTo(statusOrder::class);
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function rejected()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
    public function completed()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    // untuk accessor log activity
    public function getStatusNameAttribute()
    {
        return $this->status?->name;
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

                // Hitung ulang final_stock
                $product->final_stock = $product->stock + $product->in_stock - $product->out_stock;
                $product->save();
            }
        });
        // membuat default ketika input
        static::creating(function ($preOrder) {
            $preOrder->slug = Str::slug($preOrder->code_po);
        });
        // membuat default request untuk user
        static::creating(function ($model) {
            $model->status_id = $model->status_id ?? statusOrder::where('name', 'requested')->value('id');
        });
    }
    // memanggil menggunakan slug untuk edit, view
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
