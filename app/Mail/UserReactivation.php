<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserReactivation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $personalizedAdverts;
    public $specialOffers;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $personalizedAdverts = [], $specialOffers = [])
    {
        $this->user = $user;
        $this->personalizedAdverts = $personalizedAdverts;
        $this->specialOffers = $specialOffers;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We Miss You! Check Out What\'s New on CharyMeld',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user.reactivation',
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
