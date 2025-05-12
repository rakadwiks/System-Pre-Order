<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * 
 *
 * @property int $id
 * @property string $name_team
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $division_id
 * @property int $position_id
 * @property-read \App\Models\Division $division
 * @property-read \App\Models\Position $position
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereNameTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Team extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    // mengambil data dari tabel divisions & kolom division_id pada team
    public function division() {
        return $this->belongsTo(Division::class, 'division_id'); 
    }
    // mengambil data dari tabel positions & kolom position_id pada team
    public function position() {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
