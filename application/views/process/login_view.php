<?php include APPPATH . '/views/header_view.php' ?>

<script
	type="text/javascript"
	src="<?php echo base_url(); ?>assets/js/login.js"></script>

<div id='menu_box'><?= $menu['box'] ?></div>

<p><?php echo _('Login'); ?></p>

<div id="login_box"><?= $login['form'] ?></div>

<div id="login_box_msg"></div>

<?php include APPPATH . '/views/footer_view.php' ?>
