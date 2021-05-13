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
    public $control;
    public function __construct($data,$lstordendias,$control)
    {
        $this->data =$data;
        $this->lstordendias =$lstordendias;
        $this->control =$control;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->control=="MiembroOCS") {
            return $this->from('robot@ist17dejulio.edu.ec','Sistema OCS')
                    ->view('email.recibido')
                    ->subject('Notificación del Sitema OCS')
                    ->with($this->data,$this->lstordendias);
        }
        if ($this->control=="MiembroINV") {
            return $this->from('robot@ist17dejulio.edu.ec','Sistema OCS')
                    ->view('email.recibidoinv')
                    ->subject('Notificación del Sitema OCS')
                    ->with($this->data,$this->lstordendias);
        }
    }
}
