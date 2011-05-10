<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Modules management model
 *
 * @author Anton Raharja
 */
class SC_modules extends CI_Model {

	private $table_modules = 'sc_modules';

	function __construct() {
		parent::__construct();
	}

	/**
	 * Insert a new module to database
	 * @param array $data Array of module data to be inserted to database
	 * @return integer,boolean Module ID or FALSE when failed
	 */
	public function insert($data) {
		if ($this->db->insert($this->table_modules, $data)) {
			$module_id = $this->db->insert_id();
			if ($module_id) {
				return $module_id;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * Get all modules or specific module when $module_id given
	 * @param integer $module_id Module ID
	 * @return array Array of objects containing module items
	 */
	public function get($module_id=NULL) {
		if (isset($module_id)) {
			$query = $this->db->get_where($this->table_modules, array('id' => $module_id));
		} else {
			$query = $this->db->get_where($this->table_modules);
		}
		return $query->result();
	}

	/**
	 * Update module
	 * @param array $data Array of module data to be updated
	 * @param integer $module_id Module ID
	 * @return boolean TRUE if update success
	 */
	public function update($data, $module_id) {
		if (count($data) > 0) {
			$this->db->update($this->table_modules, $data, array('id' => $module_id));
		}
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete module
	 * @param integer $module_id Module ID
	 * @return boolean TRUE if deletion success
	 */
	public function delete($module_id) {
		$this->db->delete($this->table_modules, array('id' => $module_id));
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

/* End of file sc_modules.php */
/* Location: ./application/models/sc_modules.php */
