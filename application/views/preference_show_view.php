<?php include APPPATH . '/views/header_view.php' ?>

<div id='menu_box'><?= $menu['box'] ?><?php echo anchor('process/logout', t('Logout'), 'title=' . t('Logout')); ?></div>

<p><?php echo t('Welcome'); ?> <?= $login['first_name'] ?> <?= $login['last_name'] ?></p>

<p><?php echo t('Your role'); ?>: <?= $login['role'] ?></p>

<h1><?php echo t('User Preferences'); ?></h1>

<h2><?php echo t('Username').' : '; ?><?=$pref['username']?></h2>

<?=$crud?>

<?php include APPPATH . '/views/footer_view.php' ?>
