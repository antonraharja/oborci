<!doctype html>
<html lang="en">
<head>
<title>oborci</title>
<script
	type="text/javascript"
	src="<?php echo base_url(); ?>assets/js/jquery-1.6.min.js"></script>
<link rel="stylesheet"
	href="<?php echo base_url(); ?>assets/css/home.css" type="text/css" />
<link rel="stylesheet"
	href="<?php echo base_url(); ?>assets/css/crud.css" type="text/css" />
<link rel="stylesheet"
	href="<?php echo base_url(); ?>assets/css/form.css" type="text/css" />
</head>
<body>

<h1><?php echo t('Administration Panel'); ?></h1>

<p>
<?php echo t('Welcome'); ?> <?= $login['first_name'] ?> <?= $login['last_name'] ?><br />
<?php echo t('Your role'); ?>: <?= $login['role'] ?>
</p>

