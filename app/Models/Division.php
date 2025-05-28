<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Division extends Model
{
    use HasFactory;
    protected $guarded = [];


    // memanggil menggunakan slug untuk edit, view
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
