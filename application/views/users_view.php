<?php include APPPATH . '/views/header_view.php' ?>

<div id='menu_box'><?= $menu['box'] ?></div>

<p><?php echo t('User Management'); ?></p>

<p><?php echo t('Welcome'); ?> <?= $login['first_name'] ?> <?= $login['last_name'] ?></p>

<p><?php echo t('Your role'); ?>: <?= $login['role'] ?></p>

<p><?php echo anchor('process/logout', t('Logout'), 'title=' . t('Logout')); ?></p>

<?=$crud?>

<?php include APPPATH . '/views/footer_view.php' ?>
