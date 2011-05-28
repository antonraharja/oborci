<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Test controller
 * 
 * @property oci_users $oci_users
 * @property oci_roles $oci_roles
 * 
 * @author Anton Raharja
 *
 */
class Test extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/oci_users', 'oborci/oci_roles'));
                $this->load->model('test_model');
	}

	/**
	 * Index Page for this controller.
	 * 
	 */
	public function index($param=NULL) {
                // get preference from oci_preferences with our username is admin
                // each user has one preference
                $query = $this->oci_users->get_one('preferences', array('username' => 'admin'));
                //print_r($query->result_array());
                
                // get role from oci_roles with our username is admin
                // each user has one role
                $query = $this->oci_users->get_one('roles', array('username' => 'admin'));
                //print_r($query->result_array());
                
                // get users from oci_users with our name is Beta Testers
                // each role has many users
                $query = $this->oci_roles->get_many('users', array('name' => 'Beta Testers'));
                //print_r($query->result_array());

                // get users from oci_users with our name is Beta Testers
                // each role has many users
                $query = $this->test_model->get_many('users', array('name' => 'Beta Testers'));
                print_r($query->result_array());
                
                $this->test_model->test1();
	}
	
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */
