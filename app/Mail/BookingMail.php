<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $qr_code)
    {
        $this->name = $user->name;
        $this->qr_code = $qr_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("【Rese】ご予約ありがとうございます。")
        ->view("emails.mail")
        ->with(["name" => $this->name, "qr_code" => $this->qr_code]);
    }
}
