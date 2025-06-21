<?php

namespace App\Observers;

use App\Models\PreOrder;
use App\Models\User;
use App\Models\Roles;
use App\Models\statusOrder;
use App\Models\Ticket;
use App\Models\StatusTicket;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class PreOrderObserver
{
    /**
     * Handle the Ticket "created" event.
     */

    public function created(PreOrder $preOrder): void
    {
        // Ambil user dari relasi ticket
        $user = $preOrder->ticket->user ?? null;
        $creator = User::find($preOrder->user_id);
        if (!$user) {
            return;
        }

        // Kirim notifikasi ke database (Filament notification)
        Notification::make()
            ->title("Hore!! your order is in process {$preOrder->code_po}.")
            ->body("{$creator->name} created a new order.")
            ->sendToDatabase($user);
    }


    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(PreOrder $preOrder): void
    {
        $approvedStatusId = statusOrder::where('name', 'Approved')->value('id');
        $rejectedStatusId = statusOrder::where('name', 'Rejected')->value('id');
        $completedStatusId = statusOrder::where('name', 'Completed')->value('id');

        if ($preOrder->wasChanged('status_id')) {
            $newStatusId = $preOrder->status_id;

            if (in_array($newStatusId, [
                $approvedStatusId,
                $rejectedStatusId,
                $completedStatusId,
            ])) {
                $updater = Auth::user();

                if (!$updater) {
                    return;
                }
                $owner = $preOrder->ticket->user ?? null;
                if ($owner && $owner->id !== $updater->id) {
                    if ($preOrder->status_id == $approvedStatusId) {
                        Notification::make()
                            ->title("Order Approved: {$preOrder->code_po}")
                            ->body("Your order has been approved by {$updater->name}.")
                            ->sendToDatabase($owner);
                    } elseif ($preOrder->status_id == $rejectedStatusId) {
                        Notification::make()
                            ->title("Order Rejected: {$preOrder->cod_po}")
                            ->body("Your order has been rejected by {$updater->name}.")
                            ->sendToDatabase($owner);
                    } elseif ($preOrder->status_id == $completedStatusId) {
                        Notification::make()
                            ->title("Your order {$preOrder->code_po} has been installed on your device")
                            ->body("Your order has been completed by {$updater->name}.")
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
