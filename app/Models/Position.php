<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Position extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name_position']) // Field yang akan dilog
            ->useLogName('user')         // Nama log
            ->logOnlyDirty()             // Hanya jika field berubah
            ->setDescriptionForEvent(fn(string $eventName) => "User model has been {$eventName}");
    }
    
    use HasFactory;
    protected $guarded = [];

    protected static function booted()
    {

        // membuat default ketika input
        static::creating(function ($Position) {
            $Position->slug = Str::slug($Position->name_position);
        });

        // mengubah slug ketika update
        static::updating(function ($Position) {
            $Position->slug = Str::slug($Position->name_position);
        });
    }
    // memanggil menggunakan slug untuk edit, view
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
