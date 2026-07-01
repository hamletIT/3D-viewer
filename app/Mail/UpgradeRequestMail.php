<?php

namespace App\Mail;

use App\Models\UpgradeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UpgradeRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public UpgradeRequest $request
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Upgrade Request — ' . $this->request->plan->name . ' Plan',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.upgrade-request',
        );
    }
}
