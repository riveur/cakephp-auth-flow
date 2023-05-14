<?php

namespace App\Services;

use Cake\Event\EventListenerInterface;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Routing\Router;
use Exception;

class Authentication implements EventListenerInterface
{
    use LocatorAwareTrait, MailerAwareTrait;

    /**
     * Defines the token expiration time in minutes.
     */
    const TOKEN_EXPIRATION_TIME = 30;

    /**
     * Defines the default length for token.
     */
    const TOKEN_STRING_LENGTH = 60;

    /**
     * Send a password reset mail.
     *
     * @param string $to The email to send to
     * @return bool Status of proccess
     */
    public function sendPasswordResetMail($to)
    {
        try {
            /** @var \App\Model\Entity\PasswordReset $passwordReset */
            $token = $this->getTableLocator()
                ->get('PasswordResets')
                ->create(
                    $to,
                    $this->generateTokenString(),
                    static::TOKEN_EXPIRATION_TIME
                );

            $url = Router::url(['_name' => 'password-reset', '?' => ['token' => $token]], true);
            $email = $this->getMailer('Auth')->send('resetPassword', [$to, $url]);
        } catch (Exception $ex) {
            return false;
        }

        return true;
    }

    protected function generateTokenString($length = null)
    {
        if ($length === null) {
            $length = static::TOKEN_STRING_LENGTH;
        }

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [
            'Model.afterSave' => 'onPasswordReset'
        ];
    }

    /**
     * Triggers on password reset
     *
     * @param \Cake\Event\Event $event
     * @param \Cake\Datasource\EntityInterface $entity
     * @param ArrayAccess $options
     * @return void
     */
    public function onPasswordReset($event, $entity, $options)
    {
        $this->getTableLocator()->get('PasswordResets')->deleteAll(['email' => $entity->email]);
    }
}
