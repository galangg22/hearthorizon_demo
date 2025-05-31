<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    /**
     * Create a new message instance.
     *
     * @param  User  $user
     * @param  string  $token
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;  // Menyimpan user ke dalam properti
        $this->token = $token; // Menyimpan token reset password ke dalam properti
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Contracts\Mail\Mailable
     */
    public function build()
    {
        // Membuat URL untuk reset password dengan token
        $url = route('password.reset', ['token' => $this->token]);

        return $this->subject('Reset Your Password')
                    ->view('emails.reset-password')  // Pastikan view 'emails.reset-password' ada
                    ->with([
                        'url' => $url,  // Kirimkan URL reset password ke view
                    ]);
    }
}
