<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Home controller
 *
 * @property oci_auth $oci_auth
 * @property themes $themes
 *
 * @author Anton Raharja
 *
 */
class Welcome extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/oci_auth', 'example1/themes'));
		$this->oci_auth->validate();
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		if ($this->oci_auth->get_access()) {
			$data['menu']['box'] = $this->themes->menu_box();
			$data['login'] = $this->themes->get_login();
			$this->load->view('example1/welcome_view', $data);
		} else {
			redirect('example1/process/login');
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
