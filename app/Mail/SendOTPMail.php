<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOTPMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $otp;

    /**
     * Create a new message instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('Email verification code: '. $this->otp)
            ->view('verify.mail-otp', [
                'app_name' => "Khmer Angkor",
                'otp'      => $this->otp,
            ]);
    }
}
