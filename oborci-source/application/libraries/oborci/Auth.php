<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Authentication library
 *
 * @property oci_roles $oci_roles
 * @property oci_users $oci_users
 * 
 * @author Anton Raharja
 * @version 0.9
 * @see http://github.com/antonraharja/oborci
 */
class Auth {

	public $username = NULL;
	public $password = NULL;
	public $user_id = NULL;
	public $role_id = NULL;
	public $preference_id = NULL;

	private $login_state = FALSE;
	private $access = FALSE;
        
        private $CI = NULL;
        
	function __construct() {
                $this->CI =& get_instance();
		$this->CI->load->model(
			array(
				'oborci/oci_roles', 
				'oborci/oci_users',
                                'oborci/oci_screens',
			)
		);
		if ($this->CI->session->userdata('login_state')) {
                        $this->_id_empty();
			$this->user_id = $this->CI->session->userdata('user_id');
			$this->set_login_state(TRUE);
                        $this->_id_refill();
		}
	}

	/**
	 * Helper function to empty variables in the object
	 */
	private function _id_empty() {
                $this->username = NULL;
                $this->password = NULL;
                $this->user_id = NULL;
                $this->role_id = NULL;
                $this->preference_id = NULL;
	}

	/**
	 * Helper function to re-fill object with informations related to logged in user
	 */
	private function _id_refill() {
		$user_id = $this->user_id;
                $query = $this->CI->oci_users->get($user_id);
                $row = $query->row();
                if ($query->num_rows() > 0) {
                        $this->preference_id = $row->preference_id;
                        $this->role_id = $row->role_id;
                        $this->username = $row->username;
                        $this->password = $row->password;
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
			$this->CI->session->set_userdata($data);
                        $this->_id_refill();
			return TRUE;
		} else {
                        $this->_id_empty();
			return FALSE;
		}
	}

	/**
	 * Process logout
	 * @return NULL
	 */
	public function logout() {
		$this->CI->session->sess_destroy();
		$this->user_id = NULL;
		$this->set_login_state(FALSE);
		$data['user_id'] = $this->user_id;
		$data['login_state'] = $this->get_login_state();
		$this->CI->session->unset_userdata($data);
                $this->_id_empty();
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
                        $query = $this->CI->oci_users->get_where(array('username' => $this->username));
                        $row = $query->row();
			if (isset($row->id)) {
                                $test_password = $row->password;
                                $test_user_id = $row->id;
			}
			if (isset($test_password) && isset($test_user_id)) {
				if ($password == $test_password) {
					$this->user_id = $test_user_id;
					$this->set_login_state(TRUE);
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
			if ($this->CI->uri->rsegment(1)) {
				$uri = $this->CI->uri->rsegment(1);
			}
			if ($this->CI->uri->rsegment(2) && ($this->CI->uri->rsegment(2) != 'index')) {
				$uri .= '/' . $this->CI->uri->rsegment(2);
			}
                        $query = $this->CI->oci_roles->get_from('oci_screens', array('id' => $this->role_id));
                        foreach ($query->result() as $row) {
                                if ($row->uri == $uri) {
                                        $this->set_access(TRUE);
                                        return TRUE;
                                }
                        }
                        // special setup for user ID 1, which is suppose tobe 'admin'
                        /*
                        if ($this->user_id == 1) {
                                $this->set_access(TRUE);
                                return TRUE;
                        }*/
		}
                $this->set_access(FALSE);
                return FALSE;
	}
	
}

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */
