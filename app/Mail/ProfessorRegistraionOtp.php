<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProfessorRegistraionOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $professorOtpDetail;

    /**
     * Create a new message instance.
     *
     * @param $professorOtpDetail
     */
    public function __construct($professorOtpDetail)
    {
        $this->professorOtpDetail = $professorOtpDetail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $professorOtpDetail = $this->professorOtpDetail;
        return $this->subject('Professor account verification')->view('mail.professor-registration-otp',compact('professorOtpDetail'));
    }
}
