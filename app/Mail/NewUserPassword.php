<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUserPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $company;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $password, ?Company $company = null)
    {
        $this->user = $user;
        $this->password = $password;
        $this->company = $company;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Your New Account Details';

        if ($this->company) {
            $subject = 'Welcome to ' . $this->company->name . ' - Your Account Details';
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-user-password',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
