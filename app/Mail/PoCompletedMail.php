<?php

namespace App\Mail;

use App\Models\PreOrder;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PoCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public PreOrder $order;

    public function __construct(PreOrder $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Installation Completed')
            ->view('emails.po-completed')
            ->with([
                // kirim model ke view
                'order' => $this->order,
            ]);
    }
}


