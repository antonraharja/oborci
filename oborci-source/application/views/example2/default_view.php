<?php
if ($this->oci_auth->get_access()) {
	$this->load->view('example2/welcome_view');
} else {
	$this->load->view('example2/login_view');
}
?>

