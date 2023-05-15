<?php

/**
 * @var \App\View\AppView $this
 */
?>

<div class="large-6">
    <div class="view content">
        <h3>Profile</h3>
        <table class="vertical-table">
            <tr>
                <th scope="row"><?= __('Email') ?></th>
                <td><?= h($this->Identity->get('email')) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Username') ?></th>
                <td><?= h($this->Identity->get('username')) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Id') ?></th>
                <td><?= $this->Number->format($this->Identity->get('id')) ?></td>
            </tr>
        </table>
    </div>

    <div class="content">
        <h3>Security</h3>
        <?= $this->Form->create($passwordChangeForm) ?>
        <fieldset>
            <legend><?= __('Change password') ?></legend>
            <?= $this->Form->control('current_password') ?>
            <?= $this->Form->control('new_password') ?>
            <?= $this->Form->control('new_password_confirm') ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>