<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email']) // Field yang akan dilog
            ->useLogName('user')         // Nama log
            ->logOnlyDirty()             // Hanya jika field berubah
            ->setDescriptionForEvent(fn(string $eventName) => "User model has been {$eventName}");
    }

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'team_id',
        'password',
        'role_id',
    ];

    //mengubah string menjadi array untuk penggunakan checkboxlist
    protected $casts = [
        'role_id' => 'integer',
    ];

    // Membuat Roles agar 
    /**
     * Cek apakah user memiliki salah satu dari role yang diberikan.
     *
     * @param mixed $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {

        Log::info('Checking roles for user: ' . json_encode($this->role));  // Log untuk melacak role
        $userRoleName = $this->role?->name; // Mengambil dari model User.php

        if (is_array($roles)) {
            return in_array($userRoleName, $roles);
        }

        return $userRoleName === $roles;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function Team()
    {
        return $this->belongsTo(Team::class);
    }
    public function Ticket()
    {
        return $this->hasMany(Ticket::class);
    }

    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    // membuat default ketika input
    protected static function booted()
    {
        parent::boot();

        static::creating(function ($register) {
            $user = Auth::user();

            // Jangan isi user_id kalau modelnya adalah User
            if (!$register instanceof User && !$register->user_id && $user) {
                $register->user_id = $user->id;
            }

            if (!app()->runningInConsole() && !$register->role_id) {
                if ($user && $user->role_id == 1) {
                    $register->role_id = 3;
                } else {
                    $register->role_id = $user && $user->role_id ? $user->role_id : 3;
                }
            }
        });
    }
}
