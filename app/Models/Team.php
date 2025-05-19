<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;
    protected $guarded = [];

    // mengambil data dari tabel divisions & kolom division_id pada team
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
    // mengambil data dari tabel positions & kolom position_id pada team
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
