<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Menus controller
 *
 * @property oci_auth $oci_auth
 * @property crud $crud
 * @property oci_template $oci_template
 * @property oci_users $oci_users
 *
 * @author Anton Raharja
 *
 */
class Test extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/oci_users', 'oborci/oci_roles'));
	}

	/**
	 * Index Page for this controller.
	 * 
	 */
	public function index($param=NULL) {
                // get preference from oci_preferences with our username is admin
                // each user has one preference
                $query = $this->oci_users->get_one('oborci/oci_preferences', array('username' => 'admin'));
                print_r($query->result_array());
                
                // get users from oci_users with our name is Beta Testers
                // each role has many users
                $query = $this->oci_roles->get_many('oborci/oci_users', array('name' => 'Beta Testers'));
                print_r($query->result_array());
	}
	
}

/* End of file menus.php */
/* Location: ./application/controllers/menus.php */
