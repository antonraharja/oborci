<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Authentication model
 *
 * @property SC_preferences $SC_preferences
 * @property SC_roles $SC_roles
 * @property SC_users $SC_users
 *  *
 * @author Anton Raharja
 */
class SC_auth extends CI_Model {

	private $user_id = NULL;
	private $login_state = FALSE;

	function __construct() {
		parent::__construct();
		$this->load->model(array('SC_preferences', 'SC_roles', 'SC_users'));
		if ($this->session->userdata('login_state')) {
			$this->set_user_id($this->session->userdata('user_id'));
			$this->set_login_state(TRUE);
		}
		log_message('debug', 'SC_auth constructed');
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
	 * Get login information
	 * @param integer $user_id User ID, if user ID omitted get_login() will get user ID from session
	 * @return array,boolean Array of logged in user data or FALSE when user not authenticated
	 */
	public function get_login($user_id=NULL) {
		if (!isset($user_id)) {
			$user_id = $this->get_user_id();
			if (!isset($user_id)) {
				return FALSE;
			}
		}
		$preference_id = $this->SC_users->get_preference_id($user_id);
		$preferences = $this->SC_preferences->get($preference_id);
		$role_id = $this->SC_users->get_role_id($user_id);
		$data['user_id'] = $user_id;
		$data['preference_id'] = $preference_id;
		$data['role_id'] = $role_id;
		if (count($preferences) > 0) {
			$data['first_name'] = $preferences[0]->first_name;
			$data['last_name'] = $preferences[0]->last_name;
		}
		$role = $this->SC_roles->get($role_id);
		if (count($role) > 0) {
			$data['role'] = $role[0]->name;
		}
		return $data;
	}

}

/* End of file sc_auth.php */
/* Location: ./application/models/sc_auth.php */
