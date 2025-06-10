<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Ticket extends Model
{
    use Notifiable;
    protected $guarded = [];
    protected $casts = [
        'photos' => 'array',
        'role_id'  => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function statusTicket()
    {
        return $this->belongsTo(StatusTicket::class);
    }
    public function statusOrder()
    {
        return $this->belongsTo(statusOrder::class);
    }
    public function preOrder()
    {
        return $this->hasMany(PreOrder::class);
    }


    // memanggil menggunakan slug untuk edit, view
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    // membuat default ketika input
    protected static function booted()
    {
        static::creating(function ($ticket) {
            $user = null;

            if ($ticket->user_id) {
                $user = User::find($ticket->user_id);
            }

            if (!$user) {
                $user = Auth::user();
                if ($user && !$ticket->user_id) {
                    $ticket->user_id = $user->id;
                }
            }

            // Set role dari user jika ada, kalau tidak set nilai default (misal 'user')
            $ticket->role = $user && $user->role_id ? $user->role_id : 3;
            // atau role default id 1

            // Set default status_ticket_id kalau belum diisi
            if (empty($ticket->status_ticket_id)) {
                $ticket->status_ticket_id = StatusTicket::where('name', 'requested')->value('id');
            }

            // Set slug kalau belum ada
            if (empty($ticket->slug) && !empty($ticket->code_ticket)) {
                $ticket->slug = Str::slug($ticket->code_ticket);
            }
        });
        static::creating(
            function ($order) {
                $order->status_order_id = statusOrder::where('name', 'requested')->value('id');
            }
        );
    }
}
