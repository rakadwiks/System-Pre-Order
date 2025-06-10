<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Roles;
use App\Models\Ticket;
use Illuminate\Support\Str;
use App\Models\StatusTicket;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */

    public function created(Ticket $ticket): void
    {
        $creator = User::find($ticket->user_id);
        if (!$creator) {
            return;
        }
        $roleIds = Roles::whereIn('name', ['SuperAdmin', 'Admin'])->pluck('id')->toArray();

        $recipients = User::whereIn('role_id', $roleIds)
            ->where('id', '!=', $creator->id) // tanpa creator
            ->get();

        foreach ($recipients as $recipient) {
            Notification::make()
                ->title("New Ticket: {$ticket->code_ticket}")
                ->body("{$creator->name} created a new ticket.")
                ->sendToDatabase($recipient);
        }
    }


    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        $approvedStatusId = StatusTicket::where('name', 'Approved')->value('id');
        $rejectedStatusId = StatusTicket::where('name', 'Rejected')->value('id');

        if ($ticket->wasChanged('status_ticket_id')) {
            $newStatusId = $ticket->status_ticket_id;

            if (in_array($newStatusId, [
                $approvedStatusId,
                $rejectedStatusId,
            ])) {
                $updater = Auth::user();

                if (!$updater) {
                    return;
                }
                $owner = $ticket->user;
                if ($owner && $owner->id !== $updater->id) {
                    if ($ticket->status_ticket_id == $approvedStatusId) {
                        Notification::make()
                            ->title("Ticket Approved: {$ticket->code_ticket}")
                            ->body("Your ticket has been approved by {$updater->name}.")
                            ->sendToDatabase($owner);
                    } elseif ($ticket->status_ticket_id == $rejectedStatusId) {
                        Notification::make()
                            ->title("Ticket Rejected: {$ticket->code_ticket}")
                            ->body("Your ticket has been rejected by {$updater->name}.")
                            ->sendToDatabase($owner);
                    }
                }
            }
        }
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
