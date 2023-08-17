<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $email;
    public $user_name;

    public function __construct($email, $user_name)
    {
        $this->email = $email;
        $this->user_name = $user_name;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->from('kangsayur080@gmail.com')
            ->subject('Permintaan Pengaturan Ulang Kata Sandi Akun Anda')
            ->to($this->email)
            ->view('resetText')->with($this->user_name);
    }
}
