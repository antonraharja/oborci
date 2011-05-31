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
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
                redirect('example2/welcome');
	}

}

/* End of file welcome2.php */
/* Location: ./application/controllers/welcome2.php */
