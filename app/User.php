<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $body = $this->name . ' You are receiving this email because we received a password reset request for your account.Reset Password ' . url('password/reset', $token);
        $mail = new Message;
        $mail->setFrom('xingwen1000 <xingwen1000@126.com>')
            ->addTo($this->email)
            ->setSubject('Reset Password')
            ->setBody($body);
        $mailer = new SmtpMailer([
            'host' => 'smtp.126.com',
            'username' => 'xingwen1000',
            'password' => 'xwen8719',
            'secure' => 'ssl',
            'port' => 465
        ]);
        $rs = $mailer->send($mail);
    }
}
