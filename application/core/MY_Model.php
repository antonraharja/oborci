<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Oborci Model extends CI_Model
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
        public function _oci_set_fields() {
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
                $this->_oci_set_fields();
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
	 * Get specific data when ID is given
	 * @param integer $id ID
	 * @return array Query containing data items
	 */
	public function get($id) {
                $this->_oci_set_fields();
		$query = $this->db->get_where($this->db_table, array($this->db_key_field => $id));
		return $query;
	}

	/**
	 * Get all data
	 * @return array Query containing data items
	 */
	public function get_all() {
                $this->_oci_set_fields();
        	$query = $this->db->get_where($this->db_table);
		return $query;
	}

        /**
         * Get data by partial fields and its value
         * @param array $field_value Array of fields and its value
         * @return array Query containing data items
         */
        public function get_by($field_value) {
                $this->_oci_set_fields();
                $query = $this->db->get_where($this->db_table, $field_value);
                return $query;
        }

        /**
	 * Update data
	 * @param integer $id ID
	 * @param array $data Array of data to be updated
	 * @return boolean TRUE if update success
	 */
	public function update($id, $data) {
                $this->_oci_set_fields();
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
	 * Update all data
	 * @param array $data Array of data to be updated
	 * @return boolean TRUE if update success
	 */
	public function update_all($data) {
                $this->_oci_set_fields();
                $returns = FALSE;
		if (count($data) > 0) {
			$this->db->update($this->db_table, $data);
		}
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                return $returns;
	}

        /**
	 * Update data by partial fields and its value
	 * @param array $field_value Array of fields and its value
	 * @param array $data Array of data to be updated
	 * @return boolean TRUE if update success
	 */
	public function update_by($field_value, $data) {
                $this->_oci_set_fields();
                $returns = FALSE;
		if (count($data) > 0) {
			$this->db->update($this->db_table, $data, $field_value);
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
                $this->_oci_set_fields();
                $returns = FALSE;
		$this->db->delete($this->db_table, array($this->db_key_field => $id));
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                return $returns;
	}

	/**
	 * Delete all data
	 * @return boolean TRUE if deletion success
	 */
	public function delete_all() {
                $this->_oci_set_fields();
                $returns = FALSE;
		$this->db->delete($this->db_table);
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                return $returns;
	}

	/**
	 * Delete data by partial fields and its value
	 * @param integer $field_value Array of fields and its value
	 * @return boolean TRUE if deletion success
	 */
	public function delete_by($field_value) {
                $this->_oci_set_fields();
                $returns = FALSE;
		$this->db->delete($this->db_table, $field_value);
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                return $returns;
	}

}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */
