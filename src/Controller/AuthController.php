<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Form\ForgotPasswordForm;
use App\Form\LoginForm;
use App\Form\PasswordChangeForm;
use App\Form\PasswordResetForm;
use App\Services\Authentication;
use Authentication\PasswordHasher\PasswordHasherTrait;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Exception;

/**
 * Auth Controller
 *
 * @property \App\Model\Table\PasswordResetsTable $PasswordResets
 * @property \App\Model\Table\UsersTable $Users
 */
class AuthController extends AppController
{

    use PasswordHasherTrait;

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['login', 'forgotPassword', 'passwordReset']);
    }

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('PasswordResets');
        $this->loadModel('Users');
    }

    public function login()
    {
        $form = new LoginForm();

        if ($this->request->is('post')) {
            $isValid = $form->validate($this->request->getData());

            if (!$isValid) {
                $this->Flash->error('Some errors');
                $this->set(compact('form'));
                return;
            }
        }

        $result = $this->Authentication->getResult();
        // If the user is logged in send them away.
        if ($result->isValid()) {
            $target = $this->Authentication->getLoginRedirect() ?? '/';
            return $this->redirect($target);
        }
        if ($this->request->is('post') && !$result->isValid()) {
            $this->request = $this->request->withoutData('password');
            $this->Flash->error('Invalid credentials');
        }

        $this->set(compact('form'));
    }

    public function logout()
    {
        $this->Authentication->logout();
        return $this->redirect(['_name' => 'login']);
    }

    public function forgotPassword()
    {
        $form = new ForgotPasswordForm();
        if ($this->request->is('post')) {
            $emailSended = $form->execute($this->request->getData());

            if (count($form->getErrors()) !== 0) {
                $this->Flash->error('Some errors');
                $this->set(compact('form'));
                return;
            }

            if ($emailSended) {
                $this->Flash->success('An email will be sent to you, follow the instructions to reset your password');
            } else {
                $this->Flash->error('An error occured, try again later.');
            }

            return $this->redirect(['_name' => 'login']);
        }

        $this->set(compact('form'));
    }

    public function passwordReset()
    {

        try {
            $passwordReset = $this->_checkTokenValidity($this->request);
        } catch (Exception $ex) {
            $this->Flash->error('This page expired.');
            return $this->redirect(['_name' => 'login']);
        }

        $form = new PasswordResetForm();

        if ($this->request->is('post')) {
            $isValid = $form->execute($this->request->getData());

            if (count($form->getErrors()) !== 0) {
                $this->Flash->error('Some errors');

                $this->set(compact('form', 'passwordReset'));
                return;
            }

            if (
                $isValid &&
                $this->_processPasswordReset($this->request->getData('password'), $passwordReset->email) !== false
            ) {
                $this->Flash->success('Your password has been reset successfully !');
            } else {
                $this->Flash->error('An error occured, try again later.');
            }

            return $this->redirect(['_name' => 'login']);
        }

        $this->set(compact('form', 'passwordReset'));
    }

    public function profile()
    {
        $passwordChangeForm = new PasswordChangeForm();

        if ($this->request->is('post')) {
            $isValid = $passwordChangeForm->validate($this->request->getData());

            if (!$isValid) {
                $this->set(compact('passwordChangeForm'));
                return;
            }

            $checkPassword = $this->Users
                ->find()
                ->select(['password'])
                ->where([
                    'email' => $this->Authentication->getIdentityData('email')
                ])
                ->first();

            $checkPassword = $this->getPasswordHasher()
                ->check($this->request->getData('current_password'), $checkPassword->password);

            if ($checkPassword === false) {
                $passwordChangeForm->setErrors([
                    'current_password' => [
                        'invalid_password' => 'The password is incorrect'
                    ]
                ]);
                $this->set(compact('passwordChangeForm'));
                return;
            }

            if (
                $this->_processPasswordReset(
                    $this->request->getData('new_password'),
                    $this->Authentication->getIdentityData('email')
                ) !== false
            ) {
                $this->Flash->success('Your password has been changed successfully !');
                return $this->logout();
            } else {
                $this->Flash->error('An error occurend try again later.');
            }
        }

        $this->set(compact('passwordChangeForm'));
    }

    /**
     * Process password reset.
     *
     * @param string $newPassword
     * @param string $email
     * @return void
     */
    protected function _processPasswordReset($newPassword, $email)
    {
        $user = $this->Users->find()->where(['email' => $email])->first();
        $user = $this->Users->patchEntity($user, [
            'password' => $newPassword
        ]);

        $this->Users->getEventManager()->on(new Authentication());

        return $this->Users->save($user);
    }

    /**
     * Checks token validity.
     *
     * @param \Cake\Http\ServerRequest $request
     * @return \App\Model\Entity\PasswordReset
     * @throws Exception If `token` query param not provided or token expired
     * @throws RecordNotFoundException If token not found in database
     */
    protected function _checkTokenValidity($request)
    {
        $token = $request->getQuery('token');

        if ($token === null) {
            throw new Exception("The `token` query param must be given.");
        }

        /** @var \App\Model\Entity\PasswordReset $passwordReset */
        $passwordReset = $this->PasswordResets->find()->where(['token' => $token])->firstOrFail();

        if ($passwordReset->expires_at->lessThan(FrozenTime::now())) {
            $this->PasswordResets->delete($passwordReset);
            throw new Exception("Token expired.");
        }

        return $passwordReset;
    }
}
