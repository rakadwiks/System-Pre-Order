<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $province_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Provinces $province
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Regency extends Model
{
    protected $guarded = [];
    public $incrementing = false;

    public function province()
    {
        return $this->belongsTo(Provinces::class);
    }
}
