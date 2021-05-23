<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmPrenotazioneMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $codpren;
    public $datapren;
    public $cognome;
    public $name;
    public $persone;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $codpren, $cognome, $name, $datapren, $persone)
    {
        $this->token = $token;
        $this->codpren = $codpren;
        $this->cognome = $cognome;
        $this->name = $name;
        $this->persone = $persone;
        $this->datapren = $datapren;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('Email.confirmPrenotazione')->with([
            'token' => $this->token,
            'codpren' => $this->codpren,
            'cognome' => $this->cognome,
            'name' => $this->name,
            'persone' => $this->persone,
            'datapren' => $this->datapren
        ]);
    }
}




