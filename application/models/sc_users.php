<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Users management model
 *
 * @property SC_preferences $SC_preferences
 *
 * @author Anton Raharja
 */
class SC_users extends CI_Model {

	private $table_users = 'sc_users';

	function __construct() {
		parent::__construct();
		$this->load->model('SC_preferences');
		log_message('debug', 'SC_users constructed');
	}

	/**
	 * Insert a new user login and preferences to database
	 * $data format:
	 * $data['preferences'] = array( 'email' => $email, 'first_name' => $first_name, 'last_name' => $last_name )
	 * $data['users'] = array( 'role_id' => $role_id, 'preference_id' => $preference_id, 'username' => $username, 'password' => $password )
	 * @param array $data
	 * @return integer,boolean
	 */
	public function insert($data) {
		if (!$this->get_user_id($data['users']['username'])) {
			$preference_id = $this->SC_preferences->insert($data['preferences']);
			if ($preference_id) {
				$data['users']['preference_id'] = $preference_id;
				if ($preference_id && $this->db->insert($this->table_users, $data['users'])) {
					$user_id = $this->db->insert_id();
					if ($preference_id && $user_id) {
						return $user_id;
					} else {
						return FALSE;
					}
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
	 * Get all users or specific user login when $user_id given
	 * @param integer $user_id
	 * @return array
	 */
	public function get($user_id=NULL) {
		if (isset($user_id)) {
			$query = $this->db->get_where($this->table_users, array('id' => $user_id));
		} else {
			$query = $this->db->get_where($this->table_users);
		}
		return $query->result();
	}

	/**
	 * Update user login and preferences
	 * $data format:
	 * $data['preferences'] = array( 'email' => $email, 'first_name' => $first_name, 'last_name' => $last_name )
	 * $data['users'] = array( 'role_id' => $role_id, 'preference_id' => $preference_id, 'username' => $username, 'password' => $password )
	 * @param array $data
	 * @param integer $user_id
	 * @return boolean
	 */
	public function update($data, $user_id) {
		if ($this->get_user_id($data['users']['username'])) {
			$ok1 = FALSE;
			if (count($data['users']) > 0) {
				$this->db->update($this->table_users, $data['users'], array('id' => $user_id));
				$ok1 = $this->db->affected_rows();
			}
			$ok2 = FALSE;
			if ($ok1 && $data['users']['preference_id']) {
				if ($this->SC_preferences->update($data, $data['users']['preference_id'])) {
					$ok2 = TRUE;
				}
			}
			if ($ok1 || $ok2) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete user login and preferences
	 * @param integer $user_id
	 * @return boolean
	 */
	public function delete($user_id) {
		$preference_id = get_preference_id($user_id);
		$ok = FALSE;
		if ($preference_id) {
			if ($this->db->delete($this->table_users, array('id' => $user_id))) {
				$ok = TRUE;
				$this->SC_preferences->delete($preference_id);
			}
		}
		if ($ok) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Get user ID
	 * @param string $username
	 * @return integer,boolean
	 */
	public function get_user_id($username) {
		$query = $this->db->get_where($this->table_users, array('username' => $username));
		$returns = $query->result();
		if (count($returns) > 0) {
			$user_id = $returns[0]->id;
			if ($user_id) {
				return $user_id;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * Get role ID
	 * @param integer $user_id
	 * @return integer,boolean
	 */
	public function get_role_id($user_id) {
		$returns = $this->get($user_id);
		if (count($returns) > 0) {
			$role_id = $returns[0]->role_id;
			if ($role_id) {
				return $role_id;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * Get preference ID
	 * @param integer $user_id
	 * @return integer,boolean
	 */
	public function get_preference_id($user_id) {
		$returns = $this->get($user_id);
		if (count($returns) > 0) {
			$preference_id = $returns[0]->preference_id;
			if ($preference_id) {
				return $preference_id;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

}

/* End of file sc_users.php */
/* Location: ./application/models/sc_users.php */
