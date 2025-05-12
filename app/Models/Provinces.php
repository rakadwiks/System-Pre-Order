<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Regency> $regencies
 * @property-read int|null $regencies_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Provinces extends Model
{

    protected $guarded = [];
    public $incrementing = false;

    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }

}
