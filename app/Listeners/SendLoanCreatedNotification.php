<?php

namespace App\Listeners;

use App\Events\NewLoanCreated;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLoanCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(NewLoanCreated $event): void
    {
        $approvers = $event->approverIds; // Use the approver IDs passed from the event

        // Loop through each approver and create a notification for them
        foreach ($approvers as $approverId) {

            $notificationCount = Notification::where('user_id', $approverId)->count();

            if ($notificationCount >= 5) {
                // Delete the oldest notification if more than 5 exist
                Notification::where('user_id', $approverId)
                            ->orderBy('created_at', 'asc')
                            ->first()
                            ->delete();
            }


            Notification::create([
                'user_id' => $approverId,  // Store the approver's user ID
                'type' => 'Loan_Created',  // Notification type
                'message' => "Loan Number: {$event->loan->loan_number} - Type: {$event->loan->loan_type}", // Message content
            ]);
        }
    }
}
