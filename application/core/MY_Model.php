<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Speedcoding Model
 *
 * @author Anton Raharja
 */
class MY_Model extends CI_Model {

        function __construct() {
		parent::__construct();
	}

        /**
         * Get array from object
         * @return array Data
         */
        private function _sc_get_data() {
                $data = NULL;
                foreach ($this->db_fields as $db_field) {
                        if (isset($this->$db_field)) {
                                $data[$db_field] = $this->$db_field;
                        }
                }
                return $data;
        }
        
        /**
         * Nullify data object
         */
        private function _sc_null_data() {
                foreach ($this->db_fields as $db_field) {
                        $this->$db_field = NULL;
                }
        }
        
        /**
	 * Insert a new model to database
	 * @param array $data Array of model data to be inserted to database
	 * @return integer|boolean model ID or FALSE when failed
	 */
	public function insert($data=NULL) {
                $returns = FALSE;
                if (! isset($data)) {
                        $data = $this->_sc_get_data();
                }
		if ($this->db->insert($this->db_table, $data)) {
			$insert_id = $this->db->insert_id();
			if ($insert_id) {
				$returns = $insert_id;
			}
		}
                $this->_sc_null_data();
                return $returns;
	}

	/**
	 * Get all model or specific model when $id is given
	 * @param integer $id model ID
	 * @return array Query containing model items
	 */
	public function get($id=NULL) {
                $query = NULL;
		if (isset($id)) {
			$query = $this->db->get_where($this->db_table, array($this->db_key_field => $id));
		} else {
			$query = $this->db->get_where($this->db_table);
		}
		return $query;
	}

	/**
	 * Update model
	 * @param array $data Array of model data to be updated
	 * @param integer $id model ID
	 * @return boolean TRUE if update success
	 */
	public function update($id, $data=NULL) {
                $returns = FALSE;
                if (! isset($data)) {
                        $data = $this->_sc_get_data_array();
                }
		if (count($data) > 0) {
			$this->db->update($this->db_table, $data, array($this->db_key_field => $id));
		}
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                $this->_sc_null_data();
                return $returns;
	}

	/**
	 * Delete model
	 * @param integer $id model ID
	 * @return boolean TRUE if deletion success
	 */
	public function delete($id) {
                $returns = FALSE;
		$this->db->delete($this->db_table, array($this->db_key_field => $id));
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                return $returns;
	}

}

/* End of file sc_model.php */
/* Location: ./application/models/sc_model.php */
