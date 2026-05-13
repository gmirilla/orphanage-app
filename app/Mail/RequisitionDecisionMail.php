<?php

namespace App\Mail;

use App\Models\Requisition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequisitionDecisionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Requisition $requisition,
        public readonly User $reviewer,
        public readonly string $decision, // 'approved' or 'rejected'
    ) {}

    public function envelope(): Envelope
    {
        $prefix = $this->decision === 'approved' ? 'Approved ✓' : 'Revision Required';
        return new Envelope(
            subject: "Requisition {$prefix} — {$this->requisition->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.requisition-decision',
            with: [
                'requisition' => $this->requisition,
                'reviewer'    => $this->reviewer,
                'decision'    => $this->decision,
                'url'         => url('/requisitions/' . $this->requisition->id),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
