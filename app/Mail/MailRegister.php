<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
class MailRegister extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $phone;
    protected $email;
    protected $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$name,$phone,$token)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $formEmail = env('MAIL_FROM_ADDRESS');
        $formName = env('MAIL_FROM_NAME');
        return new Envelope(
            from: new Address($formEmail, $formName),
            subject: 'Mail Register for '.$this->email,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail.register',
            with: [
                'phone' => $this->phone,
                'email' => $this->email,
                'name' => $this->name,
                'token' => $this->token,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
