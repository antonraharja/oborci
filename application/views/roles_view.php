<?php include APPPATH . '/views/header_view.php' ?>

<div id='menu_box'><?= $menu['box'] ?></div>

<p><?php echo _('Role Management'); ?></p>

<p><?php echo _('Welcome'); ?> <?= $login['first_name'] ?> <?= $login['last_name'] ?></p>

<p><?php echo _('Your role'); ?>: <?= $login['role'] ?></p>

<p><?php echo anchor('process/logout', _('Logout'), 'title=' . _('Logout')); ?></p>

<?=$crud?>

<?php include APPPATH . '/views/footer_view.php' ?>
