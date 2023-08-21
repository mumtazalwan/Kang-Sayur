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
    public $role;

    public function __construct($email, $user_name, $role)
    {
        $this->email = $email;
        $this->user_name = $user_name;
        $this->role = $role;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        $deep_link = "";

        if ($this->role == 'user') {
            $deep_link = "https://kangsayur.page.link/ubah-kata-sandi-pengguna";
        } elseif ($this->role == 'seller') {
            $deep_link = "https://kangsayurseller.page.link/ubah-kata-sandi";
        }

        return $this->from('kangsayur080@gmail.com')
            ->subject('Permintaan Pengaturan Ulang Kata Sandi Akun Anda')
            ->to($this->email)
            ->view('resetText')->with([
                'user_name' => $this->user_name,
                'deep_link' => $deep_link,
            ]);
    }
}
