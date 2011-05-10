<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Preferences management model
 *
 * @author Anton Raharja
 */
class SC_preferences extends CI_Model {

	private $table_preferences = 'sc_preferences';

	function __construct() {
		parent::__construct();
	}

	/**
	 * Insert a new preference to database
	 * @param array $data Array of preferences data to be inserted to database
	 * @return integer,boolean Preference ID or FALSE when failed
	 */
	public function insert($data) {
		if ($this->db->insert($this->table_preferences, $data)) {
			$preference_id = $this->db->insert_id();
			if ($preference_id) {
				return $preference_id;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * Get all preferences or specific preference when $preference_id given
	 * @param integer $preference_id Preference ID
	 * @return array Array of objects containing preferences items
	 */
	public function get($preference_id=NULL) {
		if (isset($preference_id)) {
			$query = $this->db->get_where($this->table_preferences, array('id' => $preference_id));
		} else {
			$query = $this->db->get_where($this->table_preferences);
		}
		return $query->result();
	}

	/**
	 * Update preference
	 * @param array $data Array of preferences data to be updated
	 * @param integer $preference_id Preference ID
	 * @return boolean TRUE if update success
	 */
	public function update($data, $preference_id) {
		if (count($data) > 0) {
			$this->db->update($this->table_preferences, $data, array('id' => $preference_id));
		}
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete preference
	 * @param integer $preference_id Preference ID
	 * @return boolean TRUE if deletion success
	 */
	public function delete($preference_id) {
		$this->db->delete($this->table_preferences, array('id' => $preference_id));
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

/* End of file sc_preferences.php */
/* Location: ./application/models/sc_preferences.php */

