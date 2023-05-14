<?php

namespace App\Mailer;

use Cake\Mailer\Mailer;

/**
 * Auth mailer.
 */
class AuthMailer extends Mailer
{
    /**
     * Mailer's name.
     *
     * @var string
     */
    public static $name = 'Auth';

    /**
     * Build a reset password mail.
     *
     * @param string $to To email
     * @param string $url Password reset link
     * @return void
     */
    public function resetPassword($to, $url)
    {
        $this->viewBuilder()->setTemplate('password_reset');
        $this->setViewVars([
            'email' => $to,
            'url' => $url
        ])
            ->setEmailFormat('html')
            ->setTo($to)
            ->setFrom('no-reply@enterprise.com')
            ->setSubject('[Enterprise]: Request for password reset');
    }
}
