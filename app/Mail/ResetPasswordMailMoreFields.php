<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMailMoreFields extends Mailable
{
    use Queueable, SerializesModels;

    public $datamail;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datamail)
    {
        $this->datamail = $datamail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->from('moreno@tecnomedia.biz')
        ->subject('prova invio mail con più campi personalizzati')
        ->markdown('Email.passwordResetMoreFields');

    }
}
