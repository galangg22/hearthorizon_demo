<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;  // Menyimpan user ke dalam properti
    }

    /**
     * Build the message.
     */
    // VerifyEmail Mailable
public function build()
{
    // Membuat URL untuk verifikasi
    $url = route('verify.email', ['token' => $this->user->verification_token]);

    return $this->subject('Verify Your Email Address')
                ->view('emails.verify')  // Pastikan view 'emails.verify' ada
                ->with([
                    'url' => $url,  // Kirimkan URL verifikasi ke view
                ]);
}

}
