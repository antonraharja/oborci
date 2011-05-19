<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Modules management model
 *
 * @author Anton Raharja
 */
class SC_modules extends CI_Model {

        public $id = NULL;
        public $path = NULL;
        public $name = NULL;
        public $status = NULL;
        
	private $table = 'sc_modules';
        private $fields = array('id', 'path', 'name', 'status');
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
	 * Insert a new module to database
	 * @param array $data Array of module data to be inserted to database
	 * @return integer|boolean module ID or FALSE when failed
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
	 * Get all modules or specific module when $id is given
	 * @param integer $id module ID
	 * @return array Query containing module items
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
	 * Update module
	 * @param array $data Array of module data to be updated
	 * @param integer $id module ID
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
	 * Delete module
	 * @param integer $id module ID
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

/* End of file sc_modules.php */
/* Location: ./application/models/sc_modules.php */
