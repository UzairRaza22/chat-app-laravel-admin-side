<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct($admin, $token)
    {
        $this->admin = $admin;
        $this->token = $token;
    }

    /**
     * Get message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Password Reset Email',
        );
    }

    /**
     * Get message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.password-reset',
            with: [
                'name' => $this->admin->name,
                'token' => $this->token,
            ],
        );
    }

    /**
     * Get attachments for message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
