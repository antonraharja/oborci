<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Access control list management model
 *
 * @property SC_auth $SC_auth
 * @property SC_roles $SC_roles
 * @property SC_screens $SC_screens
 *
 * @author Anton Raharja
 */
class SC_ACL extends CI_Model {

	private $valid = FALSE;

	function __construct() {
		parent::__construct();
		$this->load->model(array('SC_auth', 'SC_roles', 'SC_screens'));
	}

	/**
	 * Get access state
	 * @return boolean TRUE if visitor has access to current URI
	 */
	public function get_access() {
		return $this->valid;
	}

	/**
	 * Set access state
	 * @param NULL
	 */
	private function set_access($valid) {
		$this->valid = $valid;
	}

	/**
	 * Validate if user has access to this URI
	 * @return boolean TRUE if visitor has access to current URI
	 */
	public function validate() {
		if ($this->SC_auth->get_login_state()) {
			$data = $this->SC_auth->get_login();
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

/* End of file sc_acl.php */
/* Location: ./application/models/sc_acl.php */
