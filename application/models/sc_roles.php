<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Roles management model
 *
 * @author Anton Raharja
 */
class SC_roles extends CI_Model {

	private $table_roles = 'sc_roles';
	private $table_roles_screens = 'sc_roles_screens';
	private $table_roles_menus = 'sc_roles_menus';

	function __construct() {
		parent::__construct();
		log_message('debug', 'SC_roles constructed');
	}

	/**
	 * Insert a new role to database
	 * @param array $data
	 * @return integer,boolean
	 */
	public function insert($data) {
		if ($this->db->insert($this->table_roles, $data)) {
			$role_id = $this->db->insert_id();
			if ($role_id) {
				return $role_id;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * Get all roles or specific role when $role_id given
	 * @param integer $role_id
	 * @return array
	 */
	public function get($role_id=NULL) {
		if (isset($role_id)) {
			$query = $this->db->get_where($this->table_roles, array('id' => $role_id));
		} else {
			$query = $this->db->get_where($this->table_roles);
		}
		return $query->result();
	}

	/**
	 * Update role
	 * @param array $data
	 * @param integer $role_id
	 * @return boolean
	 */
	public function update($data, $role_id) {
		if (count($data) > 0) {
			$this->db->update($this->table_roles, $data, array('id' => $role_id));
		}
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete role
	 * @param integer $role_id
	 * @return boolean
	 */
	public function delete($role_id) {
		$this->db->delete($this->table_roles, array('id' => $role_id));
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Insert screen ID to roles_screens relation database
	 * @param integer $screen_id
	 * @param integer $role_id
	 * @return boolean
	 */
	public function insert_screen_id($screen_id, $role_id) {
		$query = $this->db->get_where($this->table_roles_screens, array('role_id' => $role_id, 'screen_id' => $screen_id));
		$returns = $query->result();
		if (!(count($returns) > 0)) {
			if ($this->db->insert($this->table_roles_screens, array('role_id' => $role_id, 'screen_id' => $screen_id))) {
				$id = $this->db->insert_id();
				if ($id) {
					return $id;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		}
		return FALSE;
	}

	/**
	 * Get screens by role ID
	 * @param integer $role_id
	 * @return array,booloean
	 */
	public function get_screen_id($role_id) {
		$query = $this->db->get_where($this->table_roles_screens, array('role_id' => $role_id));
		$returns = $query->result();
		if (count($returns) > 0) {
			return $returns;
		} else {
			return FALSE;
		}
	}

	/**
	 * Get roles and screens relation ID
	 * @param integer $role_id
	 * @param integer $screen_id
	 * @return array,booloean
	 */
	public function get_roles_screens_id($role_id, $screen_id) {
		$query = $this->db->get_where($this->table_roles_screens, array('role_id' => $role_id, 'screen_id' => $screen_id));
		$returns = $query->result();
		if (count($returns) > 0) {
			return $returns[0]->id;
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete screen ID from roles_screens relation database
	 * @param integer $screen_id
	 * @param integer $role_id
	 * @return boolean
	 */
	public function delete_screen_id($screen_id, $role_id) {
		$this->db->delete($this->table_roles_screens, array('role_id' => $role_id, 'screen_id' => $screen_id));
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Insert menu ID to roles_menus relation database
	 * @param integer $menu_id
	 * @param integer $role_id
	 * @return boolean
	 */
	public function insert_menu_id($menu_id, $role_id) {
		$query = $this->db->get_where($this->table_roles_menus, array('role_id' => $role_id, 'menu_id' => $menu_id));
		$returns = $query->result();
		if (!(count($returns) > 0)) {
			if ($this->db->insert($this->table_roles_menus, array('role_id' => $role_id, 'menu_id' => $menu_id))) {
				$id = $this->db->insert_id();
				if ($id) {
					return $id;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		}
		return FALSE;
	}

	/**
	 * Get menus by role ID
	 * @param integer $role_id
	 * @return array,booloean
	 */
	public function get_menu_id($role_id) {
		$query = $this->db->get_where($this->table_roles_menus, array('role_id' => $role_id));
		$returns = $query->result();
		if (count($returns) > 0) {
			return $returns;
		} else {
			return FALSE;
		}
	}

	/**
	 * Get roles and menus relation ID
	 * @param integer $role_id
	 * @param integer $menu_id
	 * @return integer,booloean
	 */
	public function get_roles_menus_id($role_id, $menu_id) {
		$query = $this->db->get_where($this->table_roles_menus, array('role_id' => $role_id, 'menu_id' => $menu_id));
		$returns = $query->result();
		if (count($returns) > 0) {
			return $returns[0]->id;
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete menu ID from roles_menus relation database
	 * @param integer $menu_id
	 * @param integer $role_id
	 * @return boolean
	 */
	public function delete_menu_id($menu_id, $role_id) {
		$this->db->delete($this->table_roles_menus, array('role_id' => $role_id, 'menu_id' => $menu_id));
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

/* End of file sc_roles.php */
/* Location: ./application/models/sc_roles.php */
