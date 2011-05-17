<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Users management model
 *
 * @author Anton Raharja
 */
class SC_users extends CI_Model {

	public $id = NULL;
        public $role_id = NULL;
        public $preference_id = NULL;
        public $username = NULL;
        public $password = NULL;
        public $salt = NULL;
        
        private $table = 'sc_users';
        private $fields = array('id', 'role_id', 'preference_id', 'username', 'password', 'salt');
        private $key_field = 'id';

	function __construct() {
		parent::__construct();
	}

        /**
         * Get array from object
         * @return array Data
         */
        private function _sc_get_data() {
                $data = NULL;
                foreach ($this->fields as $field) {
                        if (isset($this->$field)) {
                                $data[$field] = $this->$field;
                        }
                }
                return $data;
        }
        
        /**
         * Nullify data object
         */
        private function _sc_null_data() {
                foreach ($this->fields as $field) {
                        $this->$field = NULL;
                }
        }
        
        /**
	 * Insert a new user to database
	 * @param array $data Array of user data to be inserted to database
	 * @return integer|boolean user ID or FALSE when failed
	 */
	public function insert($data=NULL) {
                $returns = FALSE;
                if (! isset($data)) {
                        $data = $this->_sc_get_data();
                }
		if ($this->db->insert($this->table, $data)) {
			$insert_id = $this->db->insert_id();
			if ($insert_id) {
				$returns = $insert_id;
			}
		}
                $this->_sc_null_data();
                return $returns;
	}

	/**
	 * Get all users or specific user when $id is given
	 * @param integer $id user ID
	 * @return array Query containing user items
	 */
	public function get($id=NULL) {
                $query = NULL;
		if (isset($id)) {
			$query = $this->db->get_where($this->table, array($this->key_field => $id));
		} else {
			$query = $this->db->get_where($this->table);
		}
		return $query;
	}

	/**
	 * Update user
	 * @param array $data Array of user data to be updated
	 * @param integer $id user ID
	 * @return boolean TRUE if update success
	 */
	public function update($id, $data=NULL) {
                $returns = FALSE;
                if (! isset($data)) {
                        $data = $this->_sc_get_data_array();
                }
		if (count($data) > 0) {
			$this->db->update($this->table, $data, array($this->key_field => $id));
		}
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                $this->_sc_null_data();
                return $returns;
	}

	/**
	 * Delete user
	 * @param integer $id user ID
	 * @return boolean TRUE if deletion success
	 */
	public function delete($id) {
                $returns = FALSE;
		$this->db->delete($this->table, array($this->key_field => $id));
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                return $returns;
	}

	/**
	 * Get user by username
	 * @param string $username Username
	 * @return object Object of user
	 */
	public function get_by_username($username) {
		$query = $this->db->get_where($this->table, array('username' => $username));
                return $query->row();
	}

}

/* End of file sc_users.php */
/* Location: ./application/models/sc_users.php */
