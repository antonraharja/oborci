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

        // VARIABLES
        // ---------------------------------------------------------------- //
        
        
        /**
         * Database table name
         * @var string
         */
        protected $db_table = NULL;
        
        /**
         * Map of field's name. If NULL the model will auto-fill this.
         * @var array
         */
        protected $db_fields = NULL;
        
        /**
         * Table's mapped primary key. If NULL the model will auto-set it.
         * @var string
         */
        protected $db_primary_key = NULL;
        
        /**
         * Table relation configuration
         * @var array
         */
        protected $db_relations = NULL;
        
        /**
         * Array of field value to be passed to methods
         * @var array
         */
        private $db_data = NULL;
        
        /**
         * Array of query returns values
         * @var array
         */
        private $db_returns = NULL;
        
        
        // CONSTRUCTS
        // ---------------------------------------------------------------- //
        
        
        private $CI = NULL;
        private $db = NULL;
        
        function __construct() {
		$this->CI =& get_instance();
                $this->db = $this->CI->db;
	}
        
        
        // PRIVATES
        // ---------------------------------------------------------------- //
        

        /**
         * Helper to get field names and set primary_key
         * @return boolean TRUE if model init succeeded
         */
        private function _oci_model_init() {
                if (isset($this->db_table) && $this->db->table_exists($this->db_table)) {
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
         * Format returns value and set the result to $this->db_returns
         * @param object $query CI query object
         * @param array $db_fields Fields map
         */
        private function _format_find_returns($query, $db_fields=NULL) {
                $this->db_returns = NULL;
                if (! is_array($db_fields)) {
                      $db_fields = $this->db_fields;  
                }
                foreach ($query->result_array() as $row) {
                        $tmp = NULL;
                        foreach ($row as $key => $val) {
                                foreach ($db_fields as $key_field => $val_field) {
                                        if ($key == $val_field) {
                                                $tmp[$key_field] = $val;
                                        }
                                }
                        }
                        $this->db_returns[] = $tmp;
                }
        }
        
        /**
         * Get from relation table with belongs_to relation (we owned by one of the other table)
         * @param string $model Foreign model name
         * @param array $field_value Search criteria
         * @return object CI active record query containing data items  
         */
        private function _find_belongs_to($model, $field_value) {
                $rules = $this->db_relations[$model];
                $returns = $this->find_where($field_value);
                $row = $returns[0];
                $returns = NULL;
                $id = $row[$rules['foreign_key']];
                if (! empty($id)) {
                        $returns = $this->CI->$model->find($id);
                }
                return $returns;
        }
        
        /**
         * Get from relation table with has_one relation (other table have one of us)
         * @param string $model Foreign model name
         * @param array $field_value Search criteria
         * @return object CI active record query containing data items  
         */
        private function _find_has_one($model, $field_value) {
                $rules = $this->db_relations[$model];
                $returns = $this->find_where($field_value);
                $row = $returns[0];
                $returns = NULL;
                $id = $row[$this->db_primary_key];
                if (! empty($id)) {
                        $returns = $this->CI->$model->find_one(array($rules['key'] => $id));
                }
                return $returns;
        }
        
        /**
         * Get from relation table with has_many relation (other table have many of us)
         * @param string $model Foreign model name
         * @param array $field_value Search criteria
         * @return object CI active record query containing data items
         */
        private function _find_has_many($model, $field_value) {
                $rules = $this->db_relations[$model];
                $returns = $this->find_where($field_value);
                $row = $returns[0];
                $returns = NULL;
                $id = $row[$this->db_primary_key];
                if (! empty($id)) {
                        $returns = $this->CI->$model->find_where(array($rules['key'] => $id));
                }
                return $returns;
        }
        
        /**
         * Get from relation table with has_and_belongs_to_many relation
         * @param string $model Foreign model name
         * @param array $field_value Search criteria
         * @return object CI active record query containing data items
         */
        private function _find_has_and_belongs_to_many($model, $field_value) {
                $rules = $this->db_relations[$model];
                $returns = $this->find_where($field_value);
                $row = $returns[0];
                $returns = NULL;
                $id = $row[$this->db_primary_key];
                if (! empty($id)) {
                        $query = $this->db->get_where($rules['join_table'], array($rules['join_key'] => $id));
                        foreach ($query->result_array() as $row) {
                                $keys[] = $row[$rules['key']];
                        }
                        $their_primary_key = $this->CI->$model->db_fields[$this->CI->$model->db_primary_key];
                        $this->db->where_in($their_primary_key, $keys);
                        $query = $this->db->get($this->CI->$model->db_table);
                }
                $this->_format_find_returns($query, $this->CI->$model->db_fields);
                $returns = $this->get_returns();
                return $returns;
        }
        
        
        // HELPERS
        // ---------------------------------------------------------------- //
        
        
        /**
         * Get find returns
         * @return array
         */
        public function get_returns() {
                return $this->db_returns;
        }
        
        
        // INSERT
        // ---------------------------------------------------------------- //
        
        
        /**
	 * Insert a new data to database
	 * @param array $data Array of data to be inserted to database
	 * @return integer|boolean Last inserted ID or FALSE when failed
	 */
	public function insert($data) {
                if (! $this->_oci_model_init()) { return NULL; };
                $data = $this->_get_map($data);
                $data = $this->before_insert($data);
                $returns = FALSE;
		if ($this->db->insert($this->db_table, $data)) {
			$insert_id = $this->db->insert_id();
			if ($insert_id) {
				$returns = $insert_id;
			}
		}
                $returns = $this->after_insert($data, $returns);
                return $returns;
	}
        
        
        // GET
        // ---------------------------------------------------------------- //
        

	/**
	 * Get specific data when ID is given
	 * @param integer $id ID
	 * @return object CI active record query containing data items
	 */
	public function find($id) {
                if (! $this->_oci_model_init()) { return NULL; };
		$query = $this->db->get_where($this->db_table, array($this->db_fields[$this->db_primary_key] => $id));
                $this->_format_find_returns($query);
                $returns = $this->get_returns();
		return $returns;
	}

	/**
	 * Get all data
	 * @return object CI active record query containing data items
	 */
	public function find_all() {
                if (! $this->_oci_model_init()) { return NULL; };
        	$query = $this->db->get($this->db_table);
                $this->_format_find_returns($query);
                $returns = $this->get_returns();
		return $returns;
	}

        /**
         * Get data by partial fields and its value
         * @param array $field_value Array of fields and its value
         * @return object CI active record query containing data items
         */
        public function find_where($field_value) {
                if (! $this->_oci_model_init()) { return NULL; };
                $field_value = $this->_get_map($field_value);
                $query = $this->db->get_where($this->db_table, $field_value);
                $this->_format_find_returns($query);
                $returns = $this->get_returns();
                return $returns;
        }

        /**
         * Get one data by partial fields and its value
         * @param array $field_value Array of fields and its value
         * @return object CI active record query containing data item
         */
        public function find_one($field_value) {
                if (! $this->_oci_model_init()) { return NULL; };
                $field_value = $this->_get_map($field_value);
                $this->db->limit(1);
                $query = $this->db->get_where($this->db_table, $field_value);
                $this->_format_find_returns($query);
                $returns = $this->get_returns();
                return $returns;
        }

        /**
         * Get from relation table with various relation type
         * @param string $model Foreign model
         * @param array $field_value Search criteria
         * @return object CI active record query containing data items
         */
        public function find_from($model, $field_value) {
                if (! $this->_oci_model_init()) { return NULL; };
                $returns = NULL;
                $rules = $this->db_relations[$model];
                if (is_array($rules)) {
                        $relation = trim(strtolower($rules['relation']));
                        switch ($relation) {
                                case 'has_one': $returns = $this->_find_has_one($model, $field_value); break;
                                case 'belongs_to': $returns = $this->_find_belongs_to($model, $field_value); break;
                                case 'has_many': $returns = $this->_find_has_many($model, $field_value); break;
                                case 'has_and_belongs_to_many': $returns = $this->_find_has_and_belongs_to_many($model, $field_value); break;
                        }
                }
                return $returns;
        }
        
        
        // UPDATE
        // ---------------------------------------------------------------- //
        
        
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
        
        
        // DELETE
        // ---------------------------------------------------------------- //

        
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
        
        
        // CALLBACKS
        // ---------------------------------------------------------------- //

        
        /**
         * Callback before insert
         * @param array $data Insert data array
         * @return array Tampered insert data array
         */
        public function before_insert($data) {
                return $data;
        }
        
        /**
         * Callback after insert
         * @param array $data Insert data array
         * @param boolean $returns Insert state result
         * @return boolean Tampered insert state result
         */
        public function after_insert($data, $returns) {
                return $returns;
        }
        
        
        // MAGIC
        // ---------------------------------------------------------------- //
        
        
        /**
         * Magic method to get wildcard method's returns
         * @param string $name Method's name
         * @param mixed $arguments Method's arguments
         * @return mixed
         */
        public function __call($name, $arguments) {
                
                // find_by_fields($arguments)
                foreach ($this->db_fields as $key => $val) {
                        if ($name == 'find_by_'.$key) {
                                $name_find_by = 'find_by_'.$key;
                                return $this->find_where(array($key => implode($arguments)));
                        }
                }
                
        }
               
}

/* End of file Oborci_Model.php */
/* Location: ./application/libraries/oborci/Oborci_Model.php */
