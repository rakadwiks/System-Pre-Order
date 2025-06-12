<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Division extends Model
{
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
