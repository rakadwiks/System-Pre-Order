<?php

namespace App\Mail;

use App\Models\PreOrder;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PoApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public PreOrder $order;


    public function __construct(PreOrder $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Your Order Has Been Approved')
            ->view('emails.po-approved')
            ->with([
                // kirim model ke view
                'order' => $this->order,
            ]);
    }
}


