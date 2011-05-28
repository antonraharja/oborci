<?php $this->load->view('header_view'); ?>

<div id='menu_box'><?= $menu['box'] ?><?php echo anchor('process/logout', t('Logout'), 'title=' . t('Logout')); ?></div>

<h1><?php echo t('User Preferences'); ?></h1>

<h2><?php echo t('Username').' : '; ?><?=$pref['username']?></h2>

<?=$crud?>

<?php $this->load->view('footer_view'); ?>
