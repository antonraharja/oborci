<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Oborci Model
 *
 * @author Anton Raharja
 */
class Oborci_Model {

        private $CI = NULL;
        private $db = NULL;
        
        function __construct() {
                $this->CI =& get_instance();
                $this->db = $this->CI->db;
	}

        /**
         * Helper to get field names and set key_field
         */
        private function _oci_set_fields() {
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
	 * @return object Query containing data items
	 */
	public function get($id) {
                $this->_oci_set_fields();
		$query = $this->db->get_where($this->db_table, array($this->db_key_field => $id));
		return $query;
	}

	/**
	 * Get all data
	 * @return object Query containing data items
	 */
	public function get_all() {
                $this->_oci_set_fields();
        	$query = $this->db->get_where($this->db_table);
		return $query;
	}

        /**
         * Get data by partial fields and its value
         * @param array $field_value Array of fields and its value
         * @return object Query containing data items
         */
        public function get_by($field_value) {
                $this->_oci_set_fields();
                $query = $this->db->get_where($this->db_table, $field_value);
                return $query;
        }

        /**
         * Get from relation table with has_one relation (we have one om the other table)
         * @param string $model_alias An alias to a foreign model name
         * @param array $field_value Search criteria
         * @return object Query containing data items  
         */
        public function get_one($model_alias, $field_value) {
                $query = NULL;
                $relation = $this->db_has_one[$model_alias];
                foreach ($relation as $from_model => $local_key) {
                        if (isset($from_model) && isset($local_key)) {
                                $query = $this->get_by($field_value);
                                $row = $query->row_array();
                                $local_key_val = $row[$local_key];
                                if (isset($local_key_val)) {
                                        $CI =& get_instance();
                                        $CI->load->model($from_model);
                                        $model_name = basename($from_model);
                                        if (isset($model_name)) {
                                                $query = $CI->$model_name->get($local_key_val);
                                                break;
                                        }
                                }
                        }
                }
                return $query;
        }
        
        /**
         * Get from relation table with has_many relation (the other table have many of us)
         * @param string $model_alias An alias to a foreign model name
         * @param array $field_value Search criteria
         * @return object Query containing data items
         */
        public function get_many($model_alias, $field_value) {
                $query = NULL;
                $relation = $this->db_has_many[$model_alias];
                foreach ($relation as $from_model => $foreign_key) {
                        if (isset($from_model) && isset($foreign_key)) {
                                $query = $this->get_by($field_value);
                                $row = $query->row_array();
                                $key_field_val = $row[$this->db_key_field];
                                if (isset($key_field_val)) {
                                        $CI =& get_instance();
                                        $CI->load->model($from_model);
                                        $model_name = basename($from_model);
                                        if (isset($model_name)) {
                                                $query = $CI->$model_name->get_by(array($foreign_key => $key_field_val));
                                                break;
                                        }
                                }
                        }
                }
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

/* End of file Oborci_Model.php */
/* Location: ./application/libraries/oborci/Oborci_Model.php */
