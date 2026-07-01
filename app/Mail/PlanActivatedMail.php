<?php

namespace App\Mail;

use App\Models\UpgradeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlanActivatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public UpgradeRequest $request
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your ' . $this->request->plan->name . ' Plan is Active!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.plan-activated',
        );
    }
}
