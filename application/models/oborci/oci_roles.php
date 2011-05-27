<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Roles management model
 *
 * @author Anton Raharja
 */
class oci_roles extends MY_Model {

	protected $db_table = 'oci_roles';

        private $db_table_roles_screens = 'oci_roles_screens';
	private $db_table_roles_menus = 'oci_roles_menus';

	function __construct() {
		parent::__construct();
	}

	/**
	 * Insert screen ID to roles_screens relation database
	 * @param integer $screen_id screen ID
	 * @param integer $role_id role ID
	 * @return integer|boolean Roles and screens relation ID or FALSE when failed
	 */
	public function insert_screen_id($screen_id, $role_id) {
		$query = $this->db->get_where($this->db_table_roles_screens, array('role_id' => $role_id, 'screen_id' => $screen_id));
		$returns = $query->num_rows();
		if (!($returns > 0)) {
			if ($this->db->insert($this->db_table_roles_screens, array('role_id' => $role_id, 'screen_id' => $screen_id))) {
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
	 * @param integer $role_id role ID
	 * @return object|booloean Object of array screen ID or FALSE when failed
	 */
	public function get_screen_id($role_id) {
		$query = $this->db->get_where($this->db_table_roles_screens, array('role_id' => $role_id));
		$returns = $query->result();
		if (isset($returns[0]->screen_id)) {
			return $returns;
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete screen ID from roles_screens relation database
	 * @param integer $screen_id screen ID
	 * @param integer $role_id role ID
	 * @return boolean TRUE if screen ID is deleted
	 */
	public function delete_screen_id($screen_id, $role_id) {
		$this->db->delete($this->db_table_roles_screens, array('role_id' => $role_id, 'screen_id' => $screen_id));
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Insert menu ID to roles_menus relation database
	 * @param integer $menu_id menu ID
	 * @param integer $role_id role ID
	 * @return integer|boolean Roles and menus relation ID or FALSE when failed
	 */
	public function insert_menu_id($menu_id, $role_id) {
		$query = $this->db->get_where($this->db_table_roles_menus, array('role_id' => $role_id, 'menu_id' => $menu_id));
		$returns = $query->num_rows();
		if (!($returns > 0)) {
			if ($this->db->insert($this->db_table_roles_menus, array('role_id' => $role_id, 'menu_id' => $menu_id))) {
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
	 * @param integer $role_id role ID
	 * @return object|booloean Object of array menu ID or FALSE when failed
	 */
	public function get_menu_id($role_id) {
		$query = $this->db->get_where($this->db_table_roles_menus, array('role_id' => $role_id));
		$returns = $query->result();
		if (isset($returns[0]->menu_id)) {
			return $returns;
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete menu ID from roles_menus relation database
	 * @param integer $menu_id menu ID
	 * @param integer $role_id role ID
	 * @return boolean TRUE if menu ID is deleted
	 */
	public function delete_menu_id($menu_id, $role_id) {
		$this->db->delete($this->db_table_roles_menus, array('role_id' => $role_id, 'menu_id' => $menu_id));
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

        /**
         * Get role and screen relation ID
         * @param integer $role_id
         * @param integer $screen_id
         * @return integer|boolean ID or FALSE when failed
         */
        public function get_roles_screens_id($role_id, $screen_id) {
                $query = $this->db->get_where($this->db_table_roles_screens, array('role_id' => $role_id, 'screen_id' => $screen_id));
                $row = $query->row();
                if (isset($row->id)) {
                        return $row->id;
                } else {
                        return FALSE;
                }
        }
        
        /**
         * Get role and menu relation ID
         * @param integer $role_id
         * @param integer $menu_id
         * @return integer|boolean ID or FALSE when failed
         */
        public function get_roles_menus_id($role_id, $menu_id) {
                $query = $this->db->get_where($this->db_table_roles_menus, array('role_id' => $role_id, 'menu_id' => $menu_id));
                $row = $query->row();
                if (isset($row->id)) {
                        return $row->id;
                } else {
                        return FALSE;
                }
        }
        
}

/* End of file oci_roles.php */
/* Location: ./application/models/oci_roles.php */
