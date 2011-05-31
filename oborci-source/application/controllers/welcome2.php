<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Home controller for example2
 *
 * @property oci_auth $oci_auth
 * @property themes $themes
 *
 * @author Anton Raharja
 *
 */
class Welcome2 extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/oci_auth'));
		$this->oci_auth->validate();
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
                $this->load->view('example2/default_view');
	}

}

/* End of file welcome2.php */
/* Location: ./application/controllers/welcome2.php */
