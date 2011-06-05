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
                    'test3_model',
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
                echo '<h2>Test 1</h2>';
                $results = $this->test_model->find_from('oci_roles', array('username' => 'manager'));
                print_r($results);

                // get roles from oci_preferences with users username is manager
                // each of user have one preferences
                echo '<h2>Test 2</h2>';
                $results = $this->test_model->find_from('oci_preferences', array('username' => 'manager'));
                print_r($results);

                echo '<h2>Test 3</h2>';
                $results = $this->test2_model->find_from('oci_users', array('name' => 'Beta Testers'));
                print_r($results);

                echo '<h2>Test 4</h2>';
                $results = $this->test2_model->find_from('oci_screens', array('name' => 'Managers'));
                print_r($results);

                echo '<h2>Test 5</h2>';
                $results = $this->test2_model->find_from('oci_menus', array('name' => 'Administrators'));
                print_r($results);

                echo '<h2>Test 6</h2>';
                $results = $this->test3_model->find_from('oci_users', array('id' => '1'));
                print_r($results);
                
                echo '<h2>Test 7</h2>';
                $results = $this->test_model->find_by_username('manager');
                print_r($results);
	}
	
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */
