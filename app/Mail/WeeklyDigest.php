<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyDigest extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;
    public $topAdverts;
    public $trendingCategories;
    public $recentBlogs;

    /**
     * Create a new message instance.
     */
    public function __construct(NewsletterSubscriber $subscriber, $topAdverts, $trendingCategories, $recentBlogs)
    {
        $this->subscriber = $subscriber;
        $this->topAdverts = $topAdverts;
        $this->trendingCategories = $trendingCategories;
        $this->recentBlogs = $recentBlogs;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Weekly Digest - Top Ads & Updates from CharyMeld',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.weekly-digest',
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
