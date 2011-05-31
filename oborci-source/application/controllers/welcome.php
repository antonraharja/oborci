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
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		redirect('example1/welcome');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
