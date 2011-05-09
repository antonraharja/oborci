<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Menus management model
 *
 * @author Anton Raharja
 */
class SC_menus extends CI_Model {

	private $table_menus = 'sc_menus';

	function __construct() {
		parent::__construct();
		log_message('debug', 'SC_menus constructed');
	}

	/**
	 * Insert a new menu to database
	 * @param array $data
	 * @return integer|boolean
	 */
	public function insert($data) {
		if ($this->db->insert($this->table_menus, $data)) {
			$menu_id = $this->db->insert_id();
			if ($menu_id) {
				return $menu_id;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * Get all menus or specific menu when $menu_id given
	 * @param integer $menu_id
	 * @return array
	 */
	public function get($menu_id=NULL) {
		if (isset($menu_id)) {
			$query = $this->db->get_where($this->table_menus, array('id' => $menu_id));
		} else {
			$query = $this->db->get_where($this->table_menus);
		}
		return $query->result();
	}

	/**
	 * Update menu
	 * @param array $data
	 * @param integer $menu_id
	 * @return boolean
	 */
	public function update($data, $menu_id) {
		if (count($data) > 0) {
			$this->db->update($this->table_menus, $data, array('id' => $menu_id));
		}
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete menu
	 * @param integer $menu_id
	 * @return boolean
	 */
	public function delete($menu_id) {
		$this->db->delete($this->table_menus, array('id' => $menu_id));
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

/* End of file sc_menus.php */
/* Location: ./application/models/sc_menus.php */
