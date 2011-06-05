<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Home controller
 *
 * @author Anton Raharja
 *
 */
class Welcome extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		$this->load->view('welcome_view');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
