<?php

/**
 * @var \App\View\AppView $this
 * @var string $email
 * @var string $url
 */
?>

<p>Hello,</p>
<p>You have requested to reset your password account for <?= $this->Html->link($email, "mailto:{$email}") ?>, here is the link to procced it.</p>
<p>Link: <?= $this->Html->link($url, $url) ?></p>
<p>If it's not you: </p>
<ul>
    <li>Please connect to your account and change your password.</li>
</ul>
<p>Thanks to use our services, </p>
<?= $this->Html->link(__('admin@enterprise.net'), 'mailto:admin@enterprise.net') ?>