<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class certificate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Certificate',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if($this->data['mark'] == 10) {
            return new Content(
                view: 'emails.Lien_certificate',

            );
        }else if($this->data['mark'] == 9 || $this->data['mark'] == 8 || $this->data['mark'] == 7){
            return new Content(
                view: 'emails.Appreciation_Certificate',

            );
        }else{
            return new Content(
                view: 'emails.failed',

            );
        }
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
