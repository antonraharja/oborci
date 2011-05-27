<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Home controller
 *
 * @property oci_auth $oci_auth
 * @property oci_template $oci_template
 *
 * @author Anton Raharja
 *
 */
class Home extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/oci_auth', 'oborci/oci_template'));
		$this->oci_auth->validate();
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		if ($this->oci_auth->get_access()) {
			$data['menu']['box'] = $this->oci_template->menu_box();
			$data['login'] = $this->oci_template->get_login();
			$this->load->view('home_view', $data);
		} else {
			redirect('process/login');
		}
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
