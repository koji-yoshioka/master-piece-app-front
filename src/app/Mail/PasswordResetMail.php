<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // 件名
        $subject = 'Reset password mail';

        $baseUrl = config('app.url');
        $token = $this->token;
        $url = "{$baseUrl}/password-reset/{$token}";

        // 送信元のアドレス
        $from = config('mail.from.address');

        return $this->from($from)
            ->subject($subject)
            // 送信メールのビュー
            ->view('mails.password_reset')
            // ビューで使う変数を渡す
            ->with('url', $url);
    }
}
