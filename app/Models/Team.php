<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Team extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function department() {
        return $this->belongsTo(Division::class, 'id_division');
    }
    public function product() {
        return $this->belongsTo(Position::class, 'id_position');
    }
}
