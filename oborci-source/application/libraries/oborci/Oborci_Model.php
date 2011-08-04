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
         * Array of query returns values
         * @var array
         */
        private $db_returns = NULL;
        
        /**
         * Array of inputs
         * @var array
         */
        private $db_data = NULL;
        
        
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
         * Helper for insert_with() with belongs_to relation
         * @param string $model_name Related model
         * @param array $model_data Array of related model data
         * @param array $data Array of data to be inserted to database
         * @return integer|boolean Last inserted ID or FALSE when failed
         */
        private function _insert_with_belongs_to($model_name, $model_data, $data) {
                $returns = FALSE;
                // insert to model
                $model_id = $this->CI->$model_name->insert($model_data);
                if ($model_id) {
                        // get foreign_key
                        $fk = $this->db_relations[$model_name]['foreign_key'];
                        $data[$fk] = $model_id;
                        // insert to ours
                        $id = $this->insert($data);
                        if ($id) {
                                // alright, good, returns it
                                $returns = $id;
                        } else {
                                // not OK, revert
                                $this->CI->$model_name->delete($model_id);
                        }
                }
                return $returns;
        }

        /**
         * Helper for insert_with() with has_one relation
         * @param string $model_name Related model
         * @param array $model_data Array of related model data
         * @param array $data Array of data to be inserted to database
         * @return integer|boolean Last inserted ID or FALSE when failed
         */
        private function _insert_with_has_one($model_name, $model_data, $data) {
                $returns = FALSE;
                // insert to ours
                $id = $this->insert($data);
                if ($id) {
                        // get key
                        $key = $this->db_relations[$model_name]['key'];
                        $model_data[$key] = $id;
                        // insert to model
                        $model_id = $this->CI->$model_name->insert($model_data);
                        if ($model_id) {
                                // alright, good, returns it
                                $returns = $id;
                        } else {
                                // not OK, revert
                                $this->delete($id);
                        }
                }
                return $returns;
        }

        /**
         * Helper for insert_with() with has_and_belongs_to_many relation
         * @param string $model_name Related model
         * @param array $model_data Array of related model data
         * @param array $data Array of data to be inserted to database
         * @return integer|boolean Last inserted ID or FALSE when failed
         */
        private function _insert_with_has_and_belongs_to_many($model_name, $model_data, $data) {
                $returns = FALSE;
                // insert to ours
                $id = $this->insert($data);
                if ($id) {
                        // insert into model
                        $model_id = $this->CI->$model_name->insert($model_data);
                        if ($model_id) {
                                $join_table = $this->db_relations[$model_name]['join_table'];
                                $join_key = $this->db_relations[$model_name]['join_key'];
                                $key = $this->db_relations[$model_name]['key'];
                                $join_data = array($join_key => $id, $key => $model_id);
                                // insert into join table
                                if ($this->db->insert($join_table, $join_data)) {
                                        // all good, returns it
                                        $returns = $id;
                                } else {
                                        // not good, delete on model
                                        $this->CI->$model_name->delete($model_id);
                                }
                        } else {
                                // not good, delete on ours
                                $this->delete($id);
                        }
                }
                return $returns;
        }

        /**
         * Get from relation table/model with belongs_to relation (we owned by one of the other table/model)
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
         * Get from relation table/model with has_one relation (other table/model have one of us)
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
         * Get from relation table/model with has_many relation (other table/model have many of us)
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
         * Get from relation table/model with has_and_belongs_to_many relation
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
        
        /**
         * Helper for update_with() with belongs_to relation
         * @param array $field_value Array of fields and its value
         * @param string $model_name Related model
         * @param array $model_data Array of related model data
         * @param array $data Array of data to be inserted to database
         * @return boolean TRUE if update success
         */
        private function _update_with_belongs_to($field_value, $model_name, $model_data, $data) {
                $their_pk = $this->CI->$model_name->db_fields[$this->CI->$model_name->db_primary_key];
                $fk = $this->db_relations[$model_name]['foreign_key'];
                $returns = $this->update_where($field_value, $data);
                if ($returns) {
                        $results = $this->find_where($field_value);
                        foreach ($results as $row) {
                                $where = array($$their_pk => $row[$fk]);
                                $this->CI->$model_name->update_where($where, $model_data);
                        }
                }
                return $returns;
        }

        /**
         * Helper for update_with() with belongs_to relation
         * @param array $field_value Array of fields and its value
         * @param string $model_name Related model
         * @param array $model_data Array of related model data
         * @param array $data Array of data to be inserted to database
         * @return boolean TRUE if update success
         */
        private function _update_with_has_one($field_value, $model_name, $model_data, $data) {
                $key = $this->db_relations[$model_name]['key'];
                $returns = $this->update_where($field_value, $data);
                if ($returns) {
                        $results = $this->find_where($field_value);
                        foreach ($results as $row) {
                                $where = array($key => $row[$this->db_primary_key]);
                                $this->CI->$model_name->update_where($where, $model_data);
                        }
                }
                return $returns;
        }

        /**
         * Helper for delete_with() with belongs_to relation
         * @param array $field_value Array of fields and its value
         * @param string $model_name Related model
         * @return boolean TRUE if delete success
         */
        private function _delete_with_belongs_to($field_value, $model_name) {
                $their_pk = $this->CI->$model_name->db_fields[$this->CI->$model_name->db_primary_key];
                $fk = $this->db_relations[$model_name]['foreign_key'];
                $results = $this->find_where($field_value);
                foreach ($results as $row) {
                        $this->CI->$model_name->delete_where(array($their_pk => $row[$fk]));
                }
                $returns = $this->delete_where($field_value);
                return $returns;
        }

        /**
         * Helper for delete_with() with has_one relation
         * @param array $field_value Array of fields and its value
         * @param string $model_name Related model
         * @return boolean TRUE if delete success
         */
        private function _delete_with_has_one($field_value, $model_name) {
                $key = $this->db_relations[$model_name]['key'];
                $results = $this->find_where($field_value);
                foreach ($results as $row) {
                        $this->CI->$model_name->delete_where(array($key => $row[$this->db_primary_key]));
                }
                $returns = $this->delete_where($field_value);
                return $returns;
        }

        /**
         * Helper for delete_with() with has_and_belongs_to_many relation
         * @param array $field_value Array of fields and its value
         * @param string $model_name Related model
         * @return boolean TRUE if delete success
         */
        private function _delete_with_has_and_belongs_to_many($field_value, $model_name) {
                $results = $this->find_where($field_value);
                foreach ($results as $row) {
                        $join_table_name = $this->db_relations[$model_name]['join_table'];
                        $join_key = $this->db_relations[$model_name]['join_key'];
                        $key = $this->db_relations[$model_name]['key'];
                        $query = $this->db->get_where($join_table_name, array($join_key => $row[$this->db_primary_key]));
                        foreach ($query->result_array() as $row2) {
                                $their_table_name = $this->CI->$model_name->db_table;
                                $their_pk = $this->CI->$model_name->db_fields[$this->CI->$model_name->db_primary_key];
                                $this->db->delete($their_table_name, array($their_pk => $row2[$key]));
                        }
                        $this->db->delete($join_table_name, array($join_key => $row[$this->db_primary_key]));
                }
                $returns = $this->delete_where($field_value);
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
        
        /**
         * Set data inputs
         * @param array $data 
         */
        public function set_data($data=NULL) {
                $this->db_data = NULL;
                if (is_array($data)) {
                        $this->db_data = $data;
                }
        }
        
        /**
         * Get data inputs
         * @return array
         */
        public function get_data() {
                return $this->db_data;
        }
        

        // INSERT
        // ---------------------------------------------------------------- //
        
        
        /**
	 * Insert a new data to database
	 * @param array $data Array of data to be inserted to database
	 * @return integer|boolean Last inserted ID or FALSE when failed
	 */
	public function insert($data=NULL) {
                if (! isset($data)) { $data = $this->get_data(); }
                if (! $this->_oci_model_init()) { return NULL; }
                $data = $this->before_insert($data);
                $data = $this->_get_map($data);
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
        
        /**
         * Insert a new data and its related model data to database
         * @param string $model_name Related model
         * @param array $model_data Array of related model data
         * @param array $data Array of data to be inserted to database
         * @return integer|boolean Last inserted ID or FALSE when failed
         */
        public function insert_with($model_name, $model_data, $data=NULL) {
                if (! isset($data)) { $data = $this->get_data(); }
                if (! $this->_oci_model_init()) { return NULL; }
                list($model_name, $model_data, $data) = $this->before_insert_with($model_name, $model_data, $data);
                $returns = FALSE;
                $relation = $this->db_relations[$model_name]['relation'];
                switch ($relation) {
                        case 'belongs_to': $returns = $this->_insert_with_belongs_to($model_name, $model_data, $data); break;
                        case 'has_one': 
                        case 'has_many': $returns = $this->_insert_with_has_one($model_name, $model_data, $data); break;
                        case 'has_and_belongs_to_many': $returns = $this->_insert_with_has_and_belongs_to_many($model_name, $model_data, $data); break;
                }
		$returns = $this->after_insert_with($model_name, $model_data, $data, $returns);
                return $returns;
        }
        
        
        // GET
        // ---------------------------------------------------------------- //
        

	/**
	 * Get specific data when ID is given
	 * @param integer $id ID
	 * @return array Results
	 */
	public function find($id) {
                if (! $this->_oci_model_init()) { return NULL; };
                $id = $this->before_find($id);
		$query = $this->db->get_where($this->db_table, array($this->db_fields[$this->db_primary_key] => $id));
                $this->_format_find_returns($query);
                $returns = $this->get_returns();
                $returns = $this->after_find($id, $returns);
		return $returns;
	}

	/**
	 * Get all data
	 * @return array Results
	 */
	public function find_all() {
                if (! $this->_oci_model_init()) { return NULL; };
                $this->before_find_all();
        	$query = $this->db->get($this->db_table);
                $this->_format_find_returns($query);
                $returns = $this->get_returns();
                $returns = $this->after_find_all($returns);
		return $returns;
	}

        /**
         * Get data by partial fields and its value
         * @param array $field_value Array of fields and its value
         * @return array Results
         */
        public function find_where($field_value) {
                if (! $this->_oci_model_init()) { return NULL; };
                $field_value = $this->before_find_where($field_value);
                $field_value = $this->_get_map($field_value);
                $query = $this->db->get_where($this->db_table, $field_value);
                $this->_format_find_returns($query);
                $returns = $this->get_returns();
                $returns = $this->after_find_where($field_value, $returns);
                return $returns;
        }

        /**
         * Get one data by partial fields and its value
         * @param array $field_value Array of fields and its value
         * @return array Results
         */
        public function find_one($field_value) {
                if (! $this->_oci_model_init()) { return NULL; };
                $field_value = $this->before_find_one($field_value);
                $field_value = $this->_get_map($field_value);
                $this->db->limit(1);
                $query = $this->db->get_where($this->db_table, $field_value);
                $this->_format_find_returns($query);
                $returns = $this->get_returns();
                $returns = $this->after_find_one($field_value, $returns);
                return $returns;
        }

        /**
         * Get from relation table/model with various relation type
         * @param string $model Foreign model
         * @param array $field_value Search criteria
         * @return array Results
         */
        public function find_from($model, $field_value) {
                if (! $this->_oci_model_init()) { return NULL; };
                list($model, $field_value) = $this->before_find_from($model, $field_value);
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
                $returns = $this->after_find_from($model, $field_value, $returns);
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
	public function update($id, $data=NULL) {
                if (! isset($data)) { $data = $this->get_data(); }
                if (! $this->_oci_model_init()) { return NULL; }
                list($id, $data) = $this->before_update($id, $data);
                $data = $this->_get_map($data);
                $returns = FALSE;
		if (count($data) > 0) {
			$this->db->update($this->db_table, $data, array($this->db_fields[$this->db_primary_key] => $id));
		}
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                $returns = $this->after_update($id, $data, $returns);
                return $returns;
	}

        /**
	 * Update all data
	 * @param array $data Array of data to be updated
	 * @return boolean TRUE if update success
	 */
	public function update_all($data=NULL) {
                if (! isset($data)) { $data = $this->get_data(); }
                if (! $this->_oci_model_init()) { return NULL; }
                $data = $this->before_update_all($data);
                $data = $this->_get_map($data);
                $returns = FALSE;
		if (count($data) > 0) {
			$this->db->update($this->db_table, $data);
		}
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                $returns = $this->after_update_all($data, $returns);
                return $returns;
	}

        /**
	 * Update data by partial fields and its value
	 * @param array $field_value Array of fields and its value
	 * @param array $data Array of data to be updated
	 * @return boolean TRUE if update success
	 */
	public function update_where($field_value, $data=NULL) {
                if (! isset($data)) { $data = $this->get_data(); }
                if (! $this->_oci_model_init()) { return NULL; }
                list($field_value, $data) = $this->before_update_where($field_value, $data);
                $field_value = $this->_get_map($field_value);
                $data = $this->_get_map($data);
                $returns = FALSE;
		if (count($data) > 0) {
			$this->db->update($this->db_table, $data, $field_value);
		}
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                $returns = $this->after_update_where($field_value, $data, $returns);
                return $returns;
	}
        
        /**
         * Update data and its related model data
         * @param array $field_value Array of fields and its value
         * @param string $model_name Related model
         * @param array $model_data Array of related model data
         * @param array $data Array of data to be updated
         * @return boolean TRUE if update success
         */
        public function update_with($field_value, $model_name, $model_data, $data=NULL) {
                if (! isset($data)) { $data = $this->get_data(); }
                if (! $this->_oci_model_init()) { return NULL; }
                list($field_value, $model_name, $model_data, $data) = $this->before_update_with($field_value, $model_name, $model_data, $data);
                $returns = FALSE;
                $relation = $this->db_relations[$model_name]['relation'];
                switch ($relation) {
                        case 'belongs_to': $returns = $this->_update_with_belongs_to($field_value, $model_name, $model_data, $data); break;
                        case 'has_one': $returns = $this->_update_with_has_one($field_value, $model_name, $model_data, $data); break;
                }
		$returns = $this->after_update_with($field_value, $model_name, $model_data, $data, $returns);
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
                $id = $this->before_delete($id);
                $returns = FALSE;
		$this->db->delete($this->db_table, array($this->db_fields[$this->db_primary_key] => $id));
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                $returns = $this->after_delete($id, $returns);
                return $returns;
	}

	/**
	 * Delete all data
	 * @return boolean TRUE if deletion success
	 */
	public function delete_all() {
                if (! $this->_oci_model_init()) { return NULL; };
                $this->before_delete_all();
                $returns = FALSE;
		$this->db->delete($this->db_table);
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                $returns = $this->after_delete_all($returns);
                return $returns;
	}

	/**
	 * Delete data by partial fields and its value
	 * @param integer $field_value Array of fields and its value
	 * @return boolean TRUE if deletion success
	 */
	public function delete_where($field_value) {
                if (! $this->_oci_model_init()) { return NULL; };
                $field_value = $this->before_delete_where($field_value);
                $field_value = $this->_get_map($field_value);
                $returns = FALSE;
		$this->db->delete($this->db_table, $field_value);
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                $returns = $this->after_delete_where($field_value, $returns);
                return $returns;
	}

        /**
         * Delete data and its related model data
         * @param array $field_value Array of fields and its value
         * @param string $model_name Related model
         * @return boolean TRUE if delete success
         */
        public function delete_with($field_value, $model_name) {
                if (! $this->_oci_model_init()) { return NULL; };
                list($field_value, $model_name) = $this->before_delete_with($field_value, $model_name);
                $returns = FALSE;
                $relation = $this->db_relations[$model_name]['relation'];
                switch ($relation) {
                        case 'belongs_to': $returns = $this->_delete_with_belongs_to($field_value, $model_name); break;
                        case 'has_one': 
                        case 'has_many': $returns = $this->_delete_with_has_one($field_value, $model_name); break;
                        case 'has_and_belongs_to_many': $returns = $this->_delete_with_has_and_belongs_to_many($field_value, $model_name); break;
                }
		$returns = $this->after_delete_with($field_value, $model_name, $returns);
                return $returns;
        }
        
        
        // CALLBACKS
        // ---------------------------------------------------------------- //

        
        /**
         * Callback before insert
         * @param array $data Insert data array
         * @return array Tampered insert data array
         */
        private function _before_insert($data) {
                return $data;
        }
        
        private function _before_insert_with($model_name, $model_data, $data) {
                return array($model_name, $model_data, $data);
        }
        
        private function _before_find($id) {
                return $id;
        }
        
        private function _before_find_all() {
        }
        
        private function _before_find_where($field_value) {
                return $field_value;
        }
        
        private function _before_find_one($field_value) {
                return $field_value;
        }
        
        private function _before_find_from($model, $field_value) {
                return array($model, $field_value);
        }
        
        private function _before_update($id, $data) {
                return array($id, $data);
        }
        
        private function _before_update_all($data) {
                return $data;
        }
        
        private function _before_update_where($field_value, $data) {
                return array($field_value, $data);
        }
        
        private function _before_update_with($field_value, $model_name, $model_data, $data) {
                return array($field_value, $model_name, $model_data, $data);
        }
        
        private function _before_delete($id) {
                return $id;
        }
        
        private function _before_delete_all() {
        }
        
        private function _before_delete_where($field_value) {
                return $field_value;
        }
        
        private function _before_delete_with($field_value, $model_name) {
                return array($field_value, $model_name);
        }
        
        /**
         * Callback after insert
         * @param array $data Insert data array
         * @param boolean $returns Insert state result
         * @return boolean Tampered insert state result
         */
        private function _after_insert($data, $returns) {
                return $returns;
        }
        
        private function _after_insert_with($model_name, $model_data, $data, $returns) {
                return $returns;
        }
        
        private function _after_find($id, $returns) {
                return $returns;
        }

        private function _after_find_all($returns) {
                return $returns;
        }

        private function _after_find_where($field_value, $returns) {
                return $returns;
        }

        private function _after_find_one($field_value, $returns) {
                return $returns;
        }

        private function _after_find_from($model, $field_value, $returns) {
                return $returns;
        }
        
        private function _after_update($id, $data, $returns) {
                return $returns;
        }

        private function _after_update_all($data, $returns) {
                return $returns;
        }

        private function _after_update_where($field_value, $data, $returns) {
                return $returns;
        }

        private function _after_update_with($field_value, $model_name, $model_data, $data, $returns) {
                return $returns;
        }

        private function _after_delete($id, $returns) {
                return $returns;
        }

        private function _after_delete_all($returns) {
                return $returns;
        }

        private function _after_delete_where($field_value, $returns) {
                return $returns;
        }

        private function _after_delete_with($field_value, $model_name, $returns) {
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
                
                // callbacks
                $callback_method = '_'.$name;
                switch($name) {
                        // before methods
                        case 'before_insert': return $this->$callback_method($arguments[0]); break;
                        case 'before_insert_with': return $this->$callback_method($arguments[0], $arguments[1], $arguments[2]); break;
                        case 'before_find': return $this->$callback_method($arguments[0]); break;
                        case 'before_find_all': return $this->$callback_method(); break;
                        case 'before_find_where': return $this->$callback_method($arguments[0]); break;
                        case 'before_find_one': return $this->$callback_method($arguments[0]); break;
                        case 'before_find_from': return $this->$callback_method($arguments[0], $arguments[1]); break;
                        case 'before_update': return $this->$callback_method($arguments[0], $arguments[1]); break;
                        case 'before_update_all': return $this->$callback_method($arguments[0]); break;
                        case 'before_update_where': return $this->$callback_method($arguments[0], $arguments[1]); break;
                        case 'before_update_with': return $this->$callback_method($arguments[0], $arguments[1], $arguments[2], $arguments[3]); break;
                        case 'before_delete': return $this->$callback_method($arguments[0]); break;
                        case 'before_delete_all': return $this->$callback_method(); break;
                        case 'before_delete_where': return $this->$callback_method($arguments[0]); break;
                        case 'before_delete_with': return $this->$callback_method($arguments[0], $arguments[1]); break;
                        // after methods
                        case 'after_insert': return $this->$callback_method($arguments[0], $arguments[1]); break;
                        case 'after_insert_with': return $this->$callback_method($arguments[0], $arguments[1], $arguments[2], $arguments[3]); break;
                        case 'after_find': return $this->$callback_method($arguments[0], $arguments[1]); break;
                        case 'after_find_all': return $this->$callback_method($arguments[0]); break;
                        case 'after_find_where': return $this->$callback_method($arguments[0], $arguments[1]); break;
                        case 'after_find_one': return $this->$callback_method($arguments[0], $arguments[1]); break;
                        case 'after_find_from': return $this->$callback_method($arguments[0], $arguments[1], $arguments[2]); break;
                        case 'after_update': return $this->$callback_method($arguments[0], $arguments[1], $arguments[2]); break;
                        case 'after_update_all': return $this->$callback_method($arguments[0], $arguments[1]); break;
                        case 'after_update_where': return $this->$callback_method($arguments[0], $arguments[1], $arguments[2]); break;
                        case 'after_update_with': return $this->$callback_method($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]); break;
                        case 'after_delete': return $this->$callback_method($arguments[0], $arguments[1]); break;
                        case 'after_delete_all': return $this->$callback_method($arguments[0]); break;
                        case 'after_delete_where': return $this->$callback_method($arguments[0], $arguments[1]); break;
                        case 'after_delete_with': return $this->$callback_method($arguments[0], $arguments[1], $arguments[2]); break;
                }
                
        }
               
}

/* End of file Oborci_Model.php */
/* Location: ./application/libraries/oborci/Oborci_Model.php */
