<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Models\Program;
use App\Models\Pwd;


class ProgramMail extends Mailable
{
    use Queueable, SerializesModels;

      /**
     * The order instance.
     *
     * @var \App\Models\Program
     * @var \App\Models\Pwd
     */
    public $program;
    public $pwd;
 
    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Program
     * @param  \App\Models\Pwd
     * 
     * @return void
     */
    public function __construct(Program $program, $pwd)
    {
        $this->program = $program;
        $this->pwd = $pwd;

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('pdaocabuayo@gmail.com', 'PDAO CABUYAO CITY'),
            subject: 'PDAO NEW PROGRAMS'
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
            view: 'mail.programTemplate',
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
