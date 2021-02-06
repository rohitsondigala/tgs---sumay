<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordOtp extends Mailable
{
    use Queueable, SerializesModels;
    public $forgotPasswordDetail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($forgotPasswordDetail)
    {
        $this->forgotPasswordDetail = $forgotPasswordDetail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $forgotPasswordDetail = $this->forgotPasswordDetail;
        return $this->subject('Forgot Password')->view('mail.forgot-password-otp',compact('forgotPasswordDetail'));
    }
}
