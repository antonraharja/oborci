<!doctype html>
<html lang="en">
<head>
<title>Oborci Example1</title>
<script
	type="text/javascript"
	src="<?php echo base_url(); ?>assets/example1/js/jquery-1.6.min.js"></script>
<script
	type="text/javascript"
	src="<?php echo base_url(); ?>assets/example1/js/jquery.tablesorter.min.js"></script>
<script
	type="text/javascript"
	src="<?php echo base_url(); ?>assets/example1/js/jquery.metadata.js"></script>
<script
	type="text/javascript"
	src="<?php echo base_url(); ?>assets/example1/js/jquery.tablesorter.pager.js"></script>
<link rel="stylesheet"
	href="<?php echo base_url(); ?>assets/example1/css/home.css" type="text/css" />
<link rel="stylesheet"
	href="<?php echo base_url(); ?>assets/example1/css/oborci/crud.css" type="text/css" />
<link rel="stylesheet"
	href="<?php echo base_url(); ?>assets/example1/css/oborci/form.css" type="text/css" />
</head>
<body>

<h1><?php echo t('Example1 Panel'); ?></h1>

<p>
<?php if ($this->oci_auth->get_access()) : ?>
<?php echo t('Welcome'); ?> <?= $login['first_name'] ?> <?= $login['last_name'] ?><br />
<?php echo t('Your role'); ?>: <?= $login['role'] ?>
<?php endif; ?>
</p>

<div id='menu_box'>
        <?= $menu['box'] ?>
<?php if ($this->oci_auth->get_access()) : ?>
        <?php echo anchor('example1/process/logout', t('Logout'), 'title=' . t('Logout')); ?>
<?php endif; ?>
</div>

<?php flush(); ?>
