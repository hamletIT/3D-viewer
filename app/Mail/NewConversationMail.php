<?php

namespace App\Mail;

use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewConversationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Conversation $conversation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Question — ' . $this->conversation->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-conversation',
        );
    }
}
