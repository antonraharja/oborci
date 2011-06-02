<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Home controller for example2
 *
 * @property auth $auth
 * @property themes $themes
 *
 * @author Anton Raharja
 *
 */
class Welcome extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->library(array('oborci/Auth'));
		$this->auth->validate();
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
                if ($this->auth->get_access()) {
                        $this->load->view('example2/app_view');
                } else {
                        $this->load->view('example2/login_view');
                }
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/example2/welcome.php */
