<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Test controller
 * 
 * @property oci_users $oci_users
 * @property oci_roles $oci_roles
 * @property test_model $test_model
 * 
 * @author Anton Raharja
 *
 */
class Test extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array(
                    'test_model', 
                    'test2_model',
                    'oborci/oci_users',
                    'oborci/oci_roles',
                    'oborci/oci_preferences',
                    'oborci/oci_screens',
                    'oborci/oci_menus',
                    ));
	}

	/**
	 * Index Page for this controller.
	 * 
	 */
	public function index($param=NULL) {
                // get roles from oci_roles with users username is manager
                // each of user have one role
                $query = $this->test_model->get_from('oci_roles', array('username' => 'manager'));
                //print_r($query->result_array());

                // get roles from oci_preferences with users username is manager
                // each of user have one preferences
                $query = $this->test_model->get_from('oci_preferences', array('username' => 'manager'));
                //print_r($query->result_array());

                $query = $this->test2_model->get_from('oci_users', array('name' => 'Beta Testers'));
                //print_r($query->result_array());

                $query = $this->test2_model->get_from('oci_screens', array('name' => 'Managers'));
                print_r($query->result_array());

                $query = $this->test2_model->get_from('oci_menus', array('name' => 'Administrators'));
                print_r($query->result_array());
	}
	
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */
