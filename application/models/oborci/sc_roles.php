<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Roles management model
 *
 * @author Anton Raharja
 */
class SC_roles extends CI_Model {

        public $id = NULL;
        public $name = NULL;
        
	private $table = 'sc_roles';
        private $fields = array('id', 'name');
        private $key_field = 'id';
        
	private $table_roles_screens = 'sc_roles_screens';
	private $table_roles_menus = 'sc_roles_menus';

	function __construct() {
		parent::__construct();
	}

        /**
         * Get array from object
         * @return array Data
         */
        private function _sc_get_data() {
                $data = NULL;
                foreach ($this->fields as $field) {
                        if (isset($this->$field)) {
                                $data[$field] = $this->$field;
                        }
                }
                return $data;
        }
        
        /**
         * Nullify data object
         */
        private function _sc_null_data() {
                foreach ($this->fields as $field) {
                        $this->$field = NULL;
                }
        }
        
        /**
	 * Insert a new role to database
	 * @param array $data Array of role data to be inserted to database
	 * @return integer|boolean role ID or FALSE when failed
	 */
	public function insert($data=NULL) {
                $returns = FALSE;
                if (! isset($data)) {
                        $data = $this->_sc_get_data();
                }
		if ($this->db->insert($this->table, $data)) {
			$insert_id = $this->db->insert_id();
			if ($insert_id) {
				$returns = $insert_id;
			}
		}
                $this->_sc_null_data();
                return $returns;
	}

	/**
	 * Get all roles or specific role when $id is given
	 * @param integer $id role ID
	 * @return array Query containing role items
	 */
	public function get($id=NULL) {
                $query = NULL;
		if (isset($id)) {
			$query = $this->db->get_where($this->table, array($this->key_field => $id));
		} else {
			$query = $this->db->get_where($this->table);
		}
		return $query;
	}

	/**
	 * Update role
	 * @param array $data Array of role data to be updated
	 * @param integer $id role ID
	 * @return boolean TRUE if update success
	 */
	public function update($id, $data=NULL) {
                $returns = FALSE;
                if (! isset($data)) {
                        $data = $this->_sc_get_data_array();
                }
		if (count($data) > 0) {
			$this->db->update($this->table, $data, array($this->key_field => $id));
		}
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                $this->_sc_null_data();
                return $returns;
	}

	/**
	 * Delete role
	 * @param integer $id role ID
	 * @return boolean TRUE if deletion success
	 */
	public function delete($id) {
                $returns = FALSE;
		$this->db->delete($this->table, array($this->key_field => $id));
		if ($this->db->affected_rows()) {
			$returns = TRUE;
		}
                return $returns;
	}

	/**
	 * Insert screen ID to roles_screens relation database
	 * @param integer $screen_id screen ID
	 * @param integer $role_id role ID
	 * @return integer|boolean Roles and screens relation ID or FALSE when failed
	 */
	public function insert_screen_id($screen_id, $role_id) {
		$query = $this->db->get_where($this->table_roles_screens, array('role_id' => $role_id, 'screen_id' => $screen_id));
		$returns = $query->num_rows();
		if (!($returns > 0)) {
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
	 * @param integer $role_id role ID
	 * @return object|booloean Object of array screen ID or FALSE when failed
	 */
	public function get_screen_id($role_id) {
		$query = $this->db->get_where($this->table_roles_screens, array('role_id' => $role_id));
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
		$this->db->delete($this->table_roles_screens, array('role_id' => $role_id, 'screen_id' => $screen_id));
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
		$query = $this->db->get_where($this->table_roles_menus, array('role_id' => $role_id, 'menu_id' => $menu_id));
		$returns = $query->num_rows();
		if (!($returns > 0)) {
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
	 * @param integer $role_id role ID
	 * @return object|booloean Object of array menu ID or FALSE when failed
	 */
	public function get_menu_id($role_id) {
		$query = $this->db->get_where($this->table_roles_menus, array('role_id' => $role_id));
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
		$this->db->delete($this->table_roles_menus, array('role_id' => $role_id, 'menu_id' => $menu_id));
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
                $query = $this->db->get_where($this->table_roles_screens, array('role_id' => $role_id, 'screen_id' => $screen_id));
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
                $query = $this->db->get_where($this->table_roles_menus, array('role_id' => $role_id, 'menu_id' => $menu_id));
                $row = $query->row();
                if (isset($row->id)) {
                        return $row->id;
                } else {
                        return FALSE;
                }
        }
        
}

/* End of file sc_roles.php */
/* Location: ./application/models/sc_roles.php */
