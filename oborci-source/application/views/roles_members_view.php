<?php $this->load->view('header_view'); ?>

<div id='menu_box'><?= $menu['box'] ?><?php echo anchor('process/logout', t('Logout'), 'title=' . t('Logout')); ?></div>

<h1><?php echo t('Role Members'); ?></h1>

<?=$crud?>

<?php $this->load->view('footer_view'); ?>
