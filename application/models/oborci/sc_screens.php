<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Screens management model
 *
 * @author Anton Raharja
 */
class SC_screens extends CI_Model {

	public $id = NULL;
        public $module_id = NULL;
        public $name = NULL;
        public $uri = NULL;
        
        private $table = 'sc_screens';
        private $fields = array('id', 'module_id', 'name', 'uri');
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
	 * Insert a new screen to database
	 * @param array $data Array of screen data to be inserted to database
	 * @return integer|boolean screen ID or FALSE when failed
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
	 * Get all screens or specific screen when $id is given
	 * @param integer $id screen ID
	 * @return array Query containing screen items
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
	 * Update screen
	 * @param array $data Array of screen data to be updated
	 * @param integer $id screen ID
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
	 * Delete screen
	 * @param integer $id screen ID
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
	 * Get screen by URI
	 * @param integer $uri URI
	 * @return object Object of screen
	 */
	public function get_by_uri($uri) {
		$query = $this->db->get_where($this->table, array('uri' => $uri));
		return $query->row();
	}

}

/* End of file sc_screens.php */
/* Location: ./application/models/sc_screens.php */
