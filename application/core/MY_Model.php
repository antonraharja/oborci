<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Oborci Model
 *
 * @author Anton Raharja
 */
class MY_Model extends CI_Model {

        function __construct() {
		parent::__construct();
	}

        /**
         * Helper to get field names and set key_field
         */
        public function _sc_set_fields() {
                if (! is_array($this->db_fields)) {
                        $fields = $this->db->field_data($this->db_table);
                        foreach ($fields as $field)
                        {
                                $field_name = $field->name;
                                $this->db_fields[] = $field_name;
                                if ($field->primary_key) {
                                        $this->db_key_field = $field->name;
                                }
                        }
                }
        }        
        
        /**
	 * Insert a new data to database
	 * @param array $data Array of data to be inserted to database
	 * @return integer|boolean Last inserted ID or FALSE when failed
	 */
	public function insert($data) {
                $this->_sc_set_fields();
                $returns = FALSE;
		if ($this->db->insert($this->db_table, $data)) {
			$insert_id = $this->db->insert_id();
			if ($insert_id) {
				$returns = $insert_id;
			}
		}
                return $returns;
	}

	/**
	 * Get all data or specific data when ID is given
	 * @param integer ID
	 * @return array Query containing data items
	 */
	public function get($id=NULL) {
                $this->_sc_set_fields();
                $query = NULL;
		if (isset($id)) {
			$query = $this->db->get_where($this->db_table, array($this->db_key_field => $id));
		} else {
			$query = $this->db->get_where($this->db_table);
		}
		return $query;
	}

	/**
	 * Update data
	 * @param array $data Array of data to be updated
	 * @param integer $id ID
	 * @return boolean TRUE if update success
	 */
	public function update($id, $data) {
                $this->_sc_set_fields();
                $returns = FALSE;
		if (count($data) > 0) {
			$this->db->update($this->db_table, $data, array($this->db_key_field => $id));
		}
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                return $returns;
	}

	/**
	 * Delete data
	 * @param integer $id ID
	 * @return boolean TRUE if deletion success
	 */
	public function delete($id) {
                $this->_sc_set_fields();
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
