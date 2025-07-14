<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Division extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name_division']) // Field yang akan dilog
            ->useLogName('user')         // Nama log
            ->logOnlyDirty()             // Hanya jika field berubah
            ->setDescriptionForEvent(fn(string $eventName) => "User model has been {$eventName}");
    }

    use HasFactory;
    protected $guarded = [];

    protected static function booted()
    {

        // membuat default ketika input
        static::creating(function ($Division) {
            $Division->slug = Str::slug($Division->name_division);
        });

        // mengubah slug ketika update
        static::updating(function ($Division) {
            $Division->slug = Str::slug($Division->name_division);
        });
    }
    // memanggil menggunakan slug untuk edit, view
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
