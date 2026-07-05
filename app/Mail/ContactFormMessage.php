<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMessage extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $appLocale,
        public string $name,
        public string $email,
        public string $subjectLine,
        public string $messageBody,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('contact@panahpress.com', __('messages.site_name')),
            replyTo: [
                new Address($this->email, $this->name),
            ],
            subject: sprintf('%s: %s', __('messages.contact_email_subject'), $this->subjectLine),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-message',
            with: [
                'locale' => $this->appLocale,
                'name' => $this->name,
                'email' => $this->email,
                'subjectLine' => $this->subjectLine,
                'messageBody' => $this->messageBody,
            ],
        );
    }
}
