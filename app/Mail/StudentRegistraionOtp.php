<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentRegistraionOtp extends Mailable
{
    use Queueable, SerializesModels;
    public $studentOtpDetail;

    /**
     * Create a new message instance.
     *
     * @param $studentOtpDetail
     */
    public function __construct($studentOtpDetail)
    {
        $this->studentOtpDetail = $studentOtpDetail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $studentOtpDetail = $this->studentOtpDetail;
        return $this->subject('Student account verification')->view('mail.student-registration-otp',compact('studentOtpDetail'));
    }
}
