<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaidaRT extends Mailable
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
        $subject = 'Visa Garanhuns - Saida de responsável tecnico da empresa';
        return $this->to($this->user->email, $this->user->name)
            ->subject($subject)
            ->view('email.SaidaRT', [
                'user' => $this->user,
                'rt' => $this->rt,
                'empresa' => $this->empresa]);
    }
}
