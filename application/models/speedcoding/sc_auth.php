<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Authentication model
 *
 * @property SC_screens $SC_screens
 * @property SC_roles $SC_roles
 * @property SC_users $SC_users
 *  *
 * @author Anton Raharja
 */
class SC_auth extends CI_Model {

	private $user_id = NULL;
	private $login_state = FALSE;
	private $access = FALSE;

	function __construct() {
		parent::__construct();
		$this->load->model(
			array(
				'speedcoding/SC_roles', 
				'speedcoding/SC_screens', 
				'speedcoding/SC_users'
			)
		);
		if ($this->session->userdata('login_state')) {
			$this->set_user_id($this->session->userdata('user_id'));
			$this->set_login_state(TRUE);
		}
	}

	/**
	 * Get user ID
	 * @return integer Current logged in user ID
	 */
	public function get_user_id() {
		return $this->user_id;
	}

	/**
	 * Set user ID
	 * @param integer $user_id User ID
	 */
	private function set_user_id($user_id) {
		$this->user_id = $user_id;
	}

	/**
	 * Get login state
	 * @return boolean TRUE if user ID is authenticated
	 */
	public function get_login_state() {
		return $this->login_state;
	}

	/**
	 * Set login state
	 * @param boolean $login_state Login state
	 */
	private function set_login_state($login_state) {
		$this->login_state = $login_state;
	}

	/**
	 * Get access state
	 * @return boolean TRUE if visitor has access to current URI
	 */
	public function get_access() {
		return $this->access;
	}

	/**
	 * Set access state
	 * @param NULL
	 */
	private function set_access($valid) {
		$this->access = $valid;
	}

	/**
	 * Process login
	 * @return boolean TRUE when login process successed
	 */
	public function login() {
		if ($this->get_login_state()) {
			$data['user_id'] = $this->get_user_id();
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
		$this->set_user_id(NULL);
		$this->set_login_state(FALSE);
		$data['user_id'] = $this->get_user_id();
		$data['login_state'] = $this->get_login_state();
		$this->session->set_userdata($data);
	}

	/**
	 * Authentication, validate username and password
	 * @param string $username Username from a login form
	 * @param string $password Password from a login form
	 * @return boolean TRUE if username and password authenticated
	 */
	public function auth($username, $password) {
		$test_password = NULL;
		$test_user_id = NULL;
		if ($username && $password) {
			$user_id = $this->SC_users->get_user_id($username);
			if ($user_id) {
				$returns = $this->SC_users->get($user_id);
				if (count($returns) > 0) {
					$test_password = $returns[0]->password;
					$test_user_id = $user_id;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
			if ($test_password && $test_user_id) {
				if ($password == $test_password) {
					$this->set_user_id($test_user_id);
					$this->set_login_state(TRUE);
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * Get user ID, preference ID and role ID information
	 * @param integer $user_id User ID, if user ID omitted get_login() will get user ID from session
	 * @return array,boolean Array of logged in user data or FALSE when user not authenticated
	 */
	public function get_login_id($user_id=NULL) {
		if (!isset($user_id)) {
			$user_id = $this->get_user_id();
			if (!isset($user_id)) {
				return FALSE;
			}
		}
		$preference_id = $this->SC_users->get_preference_id($user_id);
		$role_id = $this->SC_users->get_role_id($user_id);
		$data['user_id'] = $user_id;
		$data['preference_id'] = $preference_id;
		$data['role_id'] = $role_id;
		return $data;
	}

	/**
	 * Validate if user has access to this URI
	 * @return boolean TRUE if visitor has access to current URI
	 */
	public function validate() {
		if ($this->get_login_state()) {
			$data = $this->get_login_id();
			$role_id = $data['role_id'];
			$uri = NULL;
			if ($this->uri->rsegment(1)) {
				$uri = $this->uri->rsegment(1);
			}
			if ($this->uri->rsegment(2) && ($this->uri->rsegment(2) != 'index')) {
				$uri .= '/' . $this->uri->rsegment(2);
			}
			$returns = $this->SC_screens->get_by_uri($uri);
			if (count($returns) > 0) {
				$screen_id = $returns[0]->id;
				$id = $this->SC_roles->get_roles_screens_id($role_id, $screen_id);
				if ($id) {
					$this->set_access(TRUE);
					return $this->get_access();
				} else {
					$this->set_access(FALSE);
					return $this->get_access();
				}
			}
		} else {
			$this->set_access(FALSE);
			return $this->get_access();
		}
	}
	
}

/* End of file sc_auth.php */
/* Location: ./application/models/sc_auth.php */
