<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;

class SignupVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $token;
    public $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($admin, $token)
    {
        $this->admin = $admin;
        $this->token = $token;
        $this->verificationUrl = url("/api/admin/auth/verify-signup/{$token}");
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Signup Verification Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.otp-registration',
            with: [
                'name' => $this->admin->name,
                'otp' => $this->token,
            ],
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
