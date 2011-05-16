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
	}

	/**
	 * Insert a new user to database
	 * @param array $data Array of user data to be inserted to database
	 * @return integer,boolean user ID or FALSE when failed
	 */
	public function insert($data) {
		if ($this->db->insert($this->table_users, $data)) {
			$user_id = $this->db->insert_id();
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
	 * Get all users or specific user when $user_id given
	 * @param integer $user_id User ID
	 * @return array Array of objects containing user items
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
	 * Update user
	 * @param array $data Array of user data to be updated
	 * @param integer $user_id User ID
	 * @return boolean TRUE if update success
	 */
	public function update($data, $user_id) {
		if (count($data) > 0) {
			$this->db->update($this->table_users, $data, array('id' => $user_id));
		}
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete user
	 * @param integer $user_id User ID
	 * @return boolean TRUE if deletion success
	 */
	public function delete($user_id) {
		$this->db->delete($this->table_users, array('id' => $user_id));
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Get user ID
	 * @param string $username Username
	 * @return integer,boolean User ID or FALSE when failed
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
	 * @param integer $user_id User ID
	 * @return integer,boolean Role ID or FALSE when failed
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
	 * @param integer $user_id User ID
	 * @return integer,boolean Preference ID or FALSE when failed
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
