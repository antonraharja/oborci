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

	public $username = NULL;
	public $password = NULL;
	public $user_id = NULL;
	public $role_id = NULL;
	public $preference_id = NULL;

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
			$this->user_id = $this->session->userdata('user_id');
			$this->set_login_state(TRUE);
                        $this->get_login_id();
		}
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
	 * Get user ID, preference ID and role ID information
	 */
	private function get_login_id() {
		$user_id = $this->user_id;
                $query = $this->SC_users->get($user_id);
                $row = $query->row();
                if ($query->num_rows() > 0) {
                        $this->preference_id = $row->preference_id;
                        $this->role_id = $row->role_id;
                }
	}

	/**
	 * Process login
	 * @return boolean TRUE when login process successed
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
	public function auth($username=NULL, $password=NULL) {
		$return = FALSE;
		$test_password = NULL;
		$test_user_id = NULL;
		$username = isset($this->username) ? $this->username : $username;
		$password = isset($this->password) ? $this->password : $password;
                $this->username = $username;
                $this->password = $password;
		if ($this->username && $this->password) {
                        $row = $this->SC_users->get_by_username($this->username);
			if (isset($row->id)) {
                                $test_password = $row->password;
                                $test_user_id = $row->id;
			}
			if (isset($test_password) && isset($test_user_id)) {
				if ($password == $test_password) {
					$this->user_id = $test_user_id;
					$this->set_login_state(TRUE);
                                        $this->get_login_id();
					return TRUE;
				}
			}
		}
		$this->username = NULL;
		$this->password = NULL;
		return FALSE;
	}

	/**
	 * Validate if user has access to this URI
	 * @return NULL Validation result is accessible through get_access() method
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
			$row = $this->SC_screens->get_by_uri($uri);
			if (isset($row->id)) {
				$screen_id = $row->id;
				$id = $this->SC_roles->get_roles_screens_id($this->role_id, $screen_id);
				if ($id) {
					$this->set_access(TRUE);
				} else {
					$this->set_access(FALSE);
				}
			}
		} else {
			$this->set_access(FALSE);
		}
	}
	
}

/* End of file sc_auth.php */
/* Location: ./application/models/sc_auth.php */
