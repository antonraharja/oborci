<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Home controller
 *
 * @property oci_auth $oci_auth
 * @property oci_themes $oci_themes
 *
 * @author Anton Raharja
 *
 */
class Home extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/oci_auth', 'oborci/oci_themes'));
		$this->oci_auth->validate();
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		if ($this->oci_auth->get_access()) {
			$data['menu']['box'] = $this->oci_themes->menu_box();
			$data['login'] = $this->oci_themes->get_login();
			$this->load->view('home_view', $data);
		} else {
			redirect('process/login');
		}
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
