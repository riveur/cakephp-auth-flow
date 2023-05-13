<?php

/**
 * @var \App\View\AppView $this
 */
?>

<div class="form content">
    <div style="display: flex; justify-content: center; align-items: center;">
        <div class="large-4">
            <?= $this->Form->create() ?>
            <fieldset>
                <legend><?= __('Login') ?></legend>
                <?= $this->Form->control('email') ?>
                <?= $this->Form->control('password') ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>