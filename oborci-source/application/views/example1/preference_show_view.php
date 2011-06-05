<?php $this->load->view('example1/header_view'); ?>

<h1><?php echo t('User Preferences'); ?></h1>

<h2><?php echo t('Username').' : '; ?><?=$pref['username']?></h2>

<?=$crud?>

<?php $this->load->view('example1/footer_view'); ?>
