<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Team extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name_team']) // Field yang akan dilog
            ->useLogName('user')         // Nama log
            ->logOnlyDirty()             // Hanya jika field berubah
            ->setDescriptionForEvent(fn(string $eventName) => "User model has been {$eventName}");
    }

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

    protected static function booted()
    {

        // membuat default ketika input
        static::creating(function ($Team) {
            $Team->slug = Str::slug($Team->name_team);
        });

        // mengubah slug ketika update
        static::updating(function ($Team) {
            $Team->slug = Str::slug($Team->name_team);
        });
    }

    // memanggil menggunakan slug untuk edit, view
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
