<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Form\PasswordResetForm $form
 * @var \App\Model\Entity\PasswordReset $passwordReset
 */
?>

<div class="form content">
    <div style="display: flex; justify-content: center; align-items: center;">
        <div class="large-4">
            <?= $this->Form->create($form) ?>
            <fieldset>
                <legend><?= __('Password reset') ?></legend>
                <p>Hey <?= $passwordReset->email ?>, enter your new password to change it !</p>
                <?= $this->Form->control('password') ?>
                <?= $this->Form->control('password_confirm', ['type' => 'password']) ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>