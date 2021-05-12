<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VueMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $lstordendias;
    public function __construct($data,$lstordendias)
    {
        $this->data =$data;
        $this->lstordendias =$lstordendias;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('robot@ist17dejulio.edu.ec','Sistema OCS')
                    ->view('email.recibido')
                    ->subject('Notificación del Sitema OCS')
                    ->with($this->data,$this->lstordendias);
    }
}
