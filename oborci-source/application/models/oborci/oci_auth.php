<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Authentication model
 *
 * @property oci_screens $oci_screens
 * @property oci_roles $oci_roles
 * @property oci_roles_screens $oci_roles_screens
 * @property oci_users $oci_users
 *  *
 * @author Anton Raharja
 */
class oci_auth extends CI_Model {

	public $username = NULL;
	public $password = NULL;
	public $user_id = NULL;
	public $role_id = NULL;
	public $preference_id = NULL;

	private $login_state = FALSE;
	private $access = FALSE;
        
	function __construct() {
		$this->load->model(
			array(
				'oborci/oci_roles', 
                                'oborci/oci_roles_screens', 
				'oborci/oci_screens', 
				'oborci/oci_users'
			)
		);
		if ($this->session->userdata('login_state')) {
			$this->user_id = $this->session->userdata('user_id');
			$this->set_login_state(TRUE);
                        $this->_populate_ids();
		}
	}

	/**
	 * Helper function to get user ID, preference ID and role ID information
	 */
	private function _populate_ids() {
		$user_id = $this->user_id;
                $query = $this->oci_users->get($user_id);
                $row = $query->row();
                if ($query->num_rows() > 0) {
                        $this->preference_id = $row->preference_id;
                        $this->role_id = $row->role_id;
                }
	}

	/**
	 * Set login state
	 * @param boolean $param Login state
	 */
	private function set_login_state($param) {
		$this->login_state = $param;
	}

	/**
	 * Set access state
	 * @param boolean $param Access state
	 */
	private function set_access($param) {
		$this->access = $param;
	}

	/**
	 * Get login state
	 * @return boolean TRUE if user is authenticated
	 */
	public function get_login_state() {
		return $this->login_state;
	}

	/**
	 * Get access state
	 * @return boolean TRUE if user authenticated and has access to current URI
	 */
	public function get_access() {
		return $this->access;
	}

	/**
	 * Process login
	 * @return boolean TRUE when login process succeeded
	 */
	public function login() {
		if ($this->get_login_state()) {
			$data['user_id'] = $this->user_id;
			$data['login_state'] = $this->get_login_state();
			$this->session->set_userdata($data);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Process logout
	 * @return NULL
	 */
	public function logout() {
		$this->session->sess_destroy();
		$this->user_id = NULL;
		$this->set_login_state(FALSE);
		$data['user_id'] = $this->user_id;
		$data['login_state'] = $this->get_login_state();
		$this->session->unset_userdata($data);
	}

	/**
	 * Authentication, validate username and password, and also set user ID, role ID and preference ID in this object
	 * @param string $username Username from a login form
	 * @param string $password Password from a login form
	 * @return boolean TRUE if username and password authenticated
	 */
	public function authenticate($username=NULL, $password=NULL) {
		$return = FALSE;
		$test_password = NULL;
		$test_user_id = NULL;
		$username = isset($this->username) ? $this->username : $username;
		$password = isset($this->password) ? $this->password : $password;
                $this->username = $username;
                $this->password = $password;
		if ($this->username && $this->password) {
                        $query = $this->oci_users->get_by(array('username' => $this->username));
                        $row = $query->row();
			if (isset($row->id)) {
                                $test_password = $row->password;
                                $test_user_id = $row->id;
			}
			if (isset($test_password) && isset($test_user_id)) {
				if ($password == $test_password) {
					$this->user_id = $test_user_id;
					$this->set_login_state(TRUE);
                                        $this->_populate_ids();
					return TRUE;
				}
			}
		}
		$this->username = NULL;
		$this->password = NULL;
		return FALSE;
	}

	/**
	 * Validate if user has access to current URI
	 * @return boolean TRUE if user validated. Validation result is also accessible through get_access() method
	 */
	public function validate() {
		if ($this->get_login_state()) {
			$uri = NULL;
			if ($this->uri->rsegment(1)) {
				$uri = $this->uri->rsegment(1);
			}
			if ($this->uri->rsegment(2) && ($this->uri->rsegment(2) != 'index')) {
				$uri .= '/' . $this->uri->rsegment(2);
			}
			$query = $this->oci_screens->get_by(array('uri' => $uri));
                        $row = $query->row();
			if (isset($row->id)) {
				$screen_id = $row->id;
                                $query = $this->oci_roles_screens->get_by(array('role_id' => $this->role_id, 'screen_id' => $screen_id));
                                $id = $query->row();
				if ($id) {
					$this->set_access(TRUE);
                                        return TRUE;
				} else {
					$this->set_access(FALSE);
                                        return FALSE;
				}
			}
		} else {
			$this->set_access(FALSE);
                        return FALSE;
		}
	}
	
}

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */
