<?php

namespace App\Mail;

use App\Models\PreOrder;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PoRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $reason;
    public PreOrder $order;

    public function __construct(string $reason, PreOrder $order)
    {
        $this->reason = $reason;
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Your Ticket Has Been Rejected')
            ->view('emails.po-rejected')
            ->with([
                // kirim model ke view
                'reason' => $this->reason,
                'order' => $this->order,
            ]);
    }
}


