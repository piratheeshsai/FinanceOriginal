<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class LoanCreatedNotification extends Notification
{
    public $loan;

    public function __construct($loan)
    {
        $this->loan = $loan;
    }

    // Specify which channels the notification will be sent through
    public function via($notifiable)
    {
        return ['database']; // Send the notification via the database channel
    }

    // Define the data to be saved in the database
    public function toDatabase($notifiable)
    {
        return [
            'loan_number' => $this->loan->loan_number,
            'loan_type' => $this->loan->loan_type,
            'message' => 'A new loan has been created.',
        ];
    }
}
