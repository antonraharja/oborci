<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Preferences management model
 *
 * @author Anton Raharja
 */
class SC_preferences extends CI_Model {

        public $id = NULL;
        public $email = NULL;
        public $first_name = NULL;
        public $last_name = NULL;
        
	private $table = 'sc_preferences';
        private $fields = array('id', 'email', 'first_name', 'last_name');
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
	 * Insert a new preference to database
	 * @param array $data Array of preference data to be inserted to database
	 * @return integer|boolean preference ID or FALSE when failed
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
	 * Get all preferences or specific preference when $id is given
	 * @param integer $id preference ID
	 * @return array Query containing preference items
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
	 * Update preference
	 * @param array $data Array of preference data to be updated
	 * @param integer $id preference ID
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
	 * Delete preference
	 * @param integer $id preference ID
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

}

/* End of file sc_preferences.php */
/* Location: ./application/models/sc_preferences.php */

