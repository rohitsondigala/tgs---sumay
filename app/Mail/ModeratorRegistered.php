<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ModeratorRegistered extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $temporaryPassword;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $temporaryPassword
     */
    public function __construct($user,$temporaryPassword)
    {
        $this->user = $user;
        $this->temporaryPassword= $temporaryPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $temporaryPassword = $this->temporaryPassword;
        return $this->subject('Moderator Registration')->view('mail.moderator-registered',compact('user','temporaryPassword'));
    }
}
