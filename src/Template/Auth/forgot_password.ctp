<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Form\ForgotPasswordForm $form
 */
?>

<div class="form content">
    <div style="display: flex; justify-content: center; align-items: center;">
        <div class="large-4">
            <?= $this->Form->create($form) ?>
            <fieldset>
                <legend><?= __('Forgot password ?') ?></legend>
                <p>Enter your email and submit. We are gonna send you the instructions to reset your password.</p>
                <?= $this->Form->control('email', ['type' => 'text']) ?>
                <?= $this->Html->link(__('Back to log in ?'), ['_name' => 'login']) ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>