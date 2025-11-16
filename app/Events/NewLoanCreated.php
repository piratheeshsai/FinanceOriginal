<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Log;

class NewLoanCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $loan;
    public $approverIds;
    /**
     * Create a new event instance.
     */
    public function __construct($loan,$approvers)
    {
        Log::info('NewLoanCreated Event Initialized', ['loan' => $loan]);
        $this->loan = $loan;
        $this->approverIds = $approvers->pluck('id')->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {

        return array_map(fn($id) => "private-approver-channel.$id", $this->approverIds);

    }

    /**
     * The name of the event that will be broadcasted.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'Loan_Created';  // Ensure this matches the event name you are binding to on the frontend
    }

    public function broadcastWith()
    {
        return [
            'loan' => $this->loan
        ];
    }
}
