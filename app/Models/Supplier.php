<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Supplier extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name_supplier', 'phone', 'email', 'address', 'country', 'postal_code']) // Field yang akan dilog
            ->useLogName('user')         // Nama log
            ->logOnlyDirty()             // Hanya jika field berubah
            ->setDescriptionForEvent(fn(string $eventName) => "User model has been {$eventName}");
    }

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
