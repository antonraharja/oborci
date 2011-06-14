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
                echo '<p>Test 1</p>';
                $query = $this->test_model->find_from('oci_roles', array('username' => 'manager'));
                print_r($query);

                // get roles from oci_preferences with users username is manager
                // each of user have one preferences
                echo '<p>Test 2</p>';
                $query = $this->test_model->find_from('oci_preferences', array('username' => 'manager'));
                print_r($query);

                echo '<p>Test 3</p>';
                $query = $this->test2_model->find_from('oci_users', array('name' => 'Beta Testers'));
                print_r($query);

                echo '<p>Test 4</p>';
                $query = $this->test2_model->find_from('oci_screens', array('name' => 'Managers'));
                print_r($query);

                echo '<p>Test 5</p>';
                $query = $this->test2_model->find_from('oci_menus', array('name' => 'Administrators'));
                print_r($query);

                echo '<p>Test 6</p>';
                $query = $this->test3_model->find_from('oci_users', array('id' => '1'));
                print_r($query);
                
                echo '<p>Test 7</p>';
                $unique = mktime();
                $username = 'test'.$unique;
                $password = '123456';
                $email = $username.'@somdomain.dom';
                $first_name = 'User '.$unique;
                $last_name = 'Test Only';
                $query = $this->test2_model->find_one(array('name' => 'Administrators'));
                print_r($query);
                $data = array('username' => $username, 'password' => $password, 'role' => $query[0]['id']);
                $model_data = array('email' => $email, 'first_name' => $first_name, 'last_name' => $last_name);
                $this->test_model->insert_with('oci_preferences', $model_data, $data);
                $query = $this->test_model->find_where(array('username' => $username));
                print_r($query);
                $query = $this->test_model->find_from('oci_preferences', array('username' => $username));
                print_r($query);
	}
	
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */
