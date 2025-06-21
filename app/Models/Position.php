<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
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
