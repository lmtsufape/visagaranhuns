<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EntradaRT extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $user, $rt, $empresa;

    public function __construct($user, $rt, $empresa)
    {
        $this->user = $user;
        $this->rt = $rt;
        $this->empresa = $empresa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Visa Garanhuns - Entrada de responsável tecnico na empresa';
        return $this->to($this->user->email, $this->user->name)
            ->subject($subject)
            ->view('email.EntradaRT', [
                'user' => $this->user,
                'rt' => $this->rt,
                'empresa' => $this->empresa]);
    }
}
