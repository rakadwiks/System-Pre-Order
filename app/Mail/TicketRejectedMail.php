<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $reason;
    public Ticket $ticket;

    public function __construct(string $reason, Ticket $ticket)
    {
        $this->reason = $reason;
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this->subject('Your Ticket Has Been Rejected')
            ->view('emails.ticket-rejected')
            ->with([
                // kirim model ke view
                'reason' => $this->reason,
                'ticket' => $this->ticket,
            ]);
    }
}


