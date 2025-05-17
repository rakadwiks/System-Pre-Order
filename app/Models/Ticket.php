<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = [];
    protected $casts = [
        'photos' => 'array',
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

    // membuat default ketika input
    protected static function booted()
    {
        static::creating(function ($ticket) {
            $ticket->status_ticket_id = StatusTicket::where('name', 'requested')->value('id');
        });

        static::creating(function ($order) {
            $order->status_order_id = statusOrder::where('name', 'requested')->value('id');
        });

        static::creating(function ($ticket) {
            $ticket->slug = Str::slug($ticket->code_ticket);
        });
    }

    // memanggil menggunakan slug untuk edit, view
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
