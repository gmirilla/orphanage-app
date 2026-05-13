<?php

namespace App\Mail;

use App\Models\Requisition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequisitionSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Requisition $requisition,
        public readonly User $submitter,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Requisition Awaiting Approval — {$this->requisition->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.requisition-submitted',
            with: [
                'requisition' => $this->requisition,
                'submitter'   => $this->submitter,
                'url'         => url('/requisitions/' . $this->requisition->id),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
