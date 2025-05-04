<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
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
        'role',
    ];

    //mengubah string menjadi array untuk penggunakan checkboxlist
    protected $casts = [
        'role' => 'array',
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
        
        \Log::info('Checking roles for user: ' . json_encode($this->role));  // Log untuk melacak role
        $roles = (array) $roles;  // Pastikan parameter roles adalah array
        return collect($this->role)->intersect($roles)->isNotEmpty();  // Cek apakah ada role yang cocok
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
    public function team() {
        return $this->belongsTo(Team::class);
    }
}
