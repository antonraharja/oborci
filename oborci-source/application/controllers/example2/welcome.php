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
class Welcome extends CI_Controller {

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
                if ($this->oci_auth->get_access()) {
                        $this->load->view('example2/app_view');
                } else {
                        $this->load->view('example2/login_view');
                }
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/example2/welcome.php */
