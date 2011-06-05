<?php $this->load->view('example1/header_view'); ?>

<!-- <script
	type="text/javascript" 
	src="<?php echo base_url(); ?>assets/js/login.js"></script> -->

<p><?php echo t('Login'); ?></p>

<div id="login_box_msg"><?= $login['message'] ?></div>

<div id="login_box"><?= $login['form'] ?></div>

<?php $this->load->view('example1/footer_view'); ?>
