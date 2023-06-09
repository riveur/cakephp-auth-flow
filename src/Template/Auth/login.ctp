<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Form\LoginForm $form
 */
?>

<div class="form content">
    <div style="display: flex; justify-content: center; align-items: center;">
        <div class="large-4">
            <?= $this->Form->create($form) ?>
            <fieldset>
                <legend><?= __('Login') ?></legend>
                <?= $this->Form->control('email') ?>
                <?= $this->Form->control('password') ?>
                <?= $this->Html->link(__('Forgot password ?'), ['_name' => 'forgot-password']) ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>