<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Oborci model library
 *
 * @author Anton Raharja
 * @version 0.9
 * @see http://github.com/antonraharja/oborci
 */
class Oborci_Model {

        private $CI = NULL;
        private $db = NULL;
        
        function __construct() {
		$this->CI =& get_instance();
                $this->db = $this->CI->db;
	}

        /**
         * Helper to get field names and set primary_key
         * @return boolean TRUE if model init succeeded
         */
        private function _oci_model_init() {
                if (isset($this->db_table)) {
                        if (! is_array($this->db_fields)) {
                                $fields = $this->db->field_data($this->db_table);
                                foreach ($fields as $field)
                                {
                                        $field_name = $field->name;
                                        $this->db_fields[$field_name] = $field_name;
                                        if ($field->primary_key) {
                                                $this->db_primary_key = $field->name;
                                        }
                                }
                        }
                        return TRUE;
                } else {
                        return FALSE;
                }
        }      
        
        /**
         * Helper to get fields map
         * @param array $field_value Unmapped fields
         * @return array Mapped fields
         */
        private function _get_map($field_value) {
                $returns = NULL;
                foreach ($field_value as $field => $value) {
                        $real_field = $this->db_fields[$field];
                        $returns[$real_field] = $value;
                }
                return $returns;
        }
        
        /**
         * Get from relation table with belongs_to relation (we owned by one of the other table)
         * @param string $model Foreign model name
         * @param array $field_value Search criteria
         * @return object CI active record query containing data items  
         */
        private function _get_belongs_to($model, $field_value) {
                $rules = $this->db_relations[$model];
                $query = $this->get_where($field_value);
                $row = $query->row_array();
                $query = NULL;
                $foreign_key = $this->db_fields[$rules['foreign_key']];
                $id = $row[$foreign_key];
                if (! empty($id)) {
                        $query = $this->CI->$model->get($id);
                }
                return $query;
        }
        
        /**
         * Get from relation table with has_one relation (other table have one of us)
         * @param string $model Foreign model name
         * @param array $field_value Search criteria
         * @return object CI active record query containing data items  
         */
        private function _get_has_one($model, $field_value) {
                $rules = $this->db_relations[$model];
                $query = $this->get_where($field_value);
                $row = $query->row_array();
                $query = NULL;
                $primary_key = $this->db_fields[$this->db_primary_key];
                $id = $row[$primary_key];
                if (! empty($id)) {
                        $query = $this->CI->$model->get_one(array($rules['key'] => $id));
                }
                return $query;
        }
        
        /**
         * Get from relation table with has_many relation (other table have many of us)
         * @param string $model Foreign model name
         * @param array $field_value Search criteria
         * @return object CI active record query containing data items
         */
        private function _get_has_many($model, $field_value) {
                $rules = $this->db_relations[$model];
                $query = $this->get_where($field_value);
                $row = $query->row_array();
                $query = NULL;
                $primary_key = $this->db_fields[$this->db_primary_key];
                $id = $row[$primary_key];
                if (! empty($id)) {
                        $query = $this->CI->$model->get_where(array($rules['key'] => $id));
                }
                return $query;
        }
        
        /**
         * Get from relation table with has_and_belongs_to_many relation
         * @param string $model Foreign model name
         * @param array $field_value Search criteria
         * @return object CI active record query containing data items
         */
        private function _get_has_and_belongs_to_many($model, $field_value) {
                $rules = $this->db_relations[$model];
                $query = $this->get_where($field_value);
                $row = $query->row_array();
                $query = NULL;
                $primary_key = $this->db_fields[$this->db_primary_key];
                $id = $row[$primary_key];
                if (! empty($id)) {
                        $query = $this->db->get_where($rules['join_table'], array($rules['join_key'] => $id));
                        foreach ($query->result_array() as $row) {
                                $keys[] = $row[$rules['key']];
                        }
                        $their_primary_key = $this->CI->$model->db_fields[$this->CI->$model->db_primary_key];
                        $this->db->where_in($their_primary_key, $keys);
                        $query = $this->db->get($this->CI->$model->db_table);
                }
                return $query;
        }
        
        /**
	 * Insert a new data to database
	 * @param array $data Array of data to be inserted to database
	 * @return integer|boolean Last inserted ID or FALSE when failed
	 */
	public function insert($data) {
                if (! $this->_oci_model_init()) { return NULL; };
                $data = $this->_get_map($data);
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
	 * @return object CI active record query containing data items
	 */
	public function get($id) {
                if (! $this->_oci_model_init()) { return NULL; };
		$query = $this->db->get_where($this->db_table, array($this->db_fields[$this->db_primary_key] => $id));
		return $query;
	}

	/**
	 * Get all data
	 * @return object CI active record query containing data items
	 */
	public function get_all() {
                if (! $this->_oci_model_init()) { return NULL; };
        	$query = $this->db->get($this->db_table);
		return $query;
	}

        /**
         * Get data by partial fields and its value
         * @param array $field_value Array of fields and its value
         * @return object CI active record query containing data items
         */
        public function get_where($field_value) {
                if (! $this->_oci_model_init()) { return NULL; };
                $field_value = $this->_get_map($field_value);
                $query = $this->db->get_where($this->db_table, $field_value);
                return $query;
        }

        /**
         * Get one data by partial fields and its value
         * @param array $field_value Array of fields and its value
         * @return object CI active record query containing data item
         */
        public function get_one($field_value) {
                if (! $this->_oci_model_init()) { return NULL; };
                $field_value = $this->_get_map($field_value);
                $this->db->limit(1);
                $query = $this->db->get_where($this->db_table, $field_value);
                return $query;
        }

        /**
         * Get from relation table with various relation type
         * @param string $model Foreign model
         * @param array $field_value Search criteria
         * @return object CI active record query containing data items
         */
        public function get_from($model, $field_value) {
                if (! $this->_oci_model_init()) { return NULL; };
                $query = NULL;
                $rules = $this->db_relations[$model];
                if (is_array($rules)) {
                        $relation = trim(strtolower($rules['relation']));
                        switch ($relation) {
                                case 'has_one': $query = $this->_get_has_one($model, $field_value); break;
                                case 'belongs_to': $query = $this->_get_belongs_to($model, $field_value); break;
                                case 'has_many': $query = $this->_get_has_many($model, $field_value); break;
                                case 'has_and_belongs_to_many': $query = $this->_get_has_and_belongs_to_many($model, $field_value); break;
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
                if (! $this->_oci_model_init()) { return NULL; };
                $returns = FALSE;
		if (count($data) > 0) {
			$this->db->update($this->db_table, $data, array($this->db_fields[$this->db_primary_key] => $id));
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
                if (! $this->_oci_model_init()) { return NULL; };
                $data = $this->_get_map($data);
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
	public function update_where($field_value, $data) {
                if (! $this->_oci_model_init()) { return NULL; };
                $field_value = $this->_get_map($field_value);
                $data = $this->_get_map($data);
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
                if (! $this->_oci_model_init()) { return NULL; };
                $returns = FALSE;
		$this->db->delete($this->db_table, array($this->db_fields[$this->db_primary_key] => $id));
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
                if (! $this->_oci_model_init()) { return NULL; };
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
	public function delete_where($field_value) {
                if (! $this->_oci_model_init()) { return NULL; };
                $returns = FALSE;
                $field_value = $this->_get_map($field_value);
		$this->db->delete($this->db_table, $field_value);
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                return $returns;
	}

}

/* End of file Oborci_Model.php */
/* Location: ./application/libraries/oborci/Oborci_Model.php */
