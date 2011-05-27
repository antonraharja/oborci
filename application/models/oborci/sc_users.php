<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Users management model
 *
 * @author Anton Raharja
 */
class SC_users extends MY_Model {

	public $id = NULL;
        public $role_id = NULL;
        public $preference_id = NULL;
        public $username = NULL;
        public $password = NULL;
        public $salt = NULL;
        
        protected $db_table = 'sc_users';
        protected $db_fields = array('id', 'role_id', 'preference_id', 'username', 'password', 'salt');
        protected $db_key_field = 'id';

	function __construct() {
		parent::__construct();
	}

	/**
	 * Get user by username
	 * @param string $username Username
	 * @return object Object of user
	 */
	public function get_by_username($username) {
		$query = $this->db->get_where($this->db_table, array('username' => $username));
                return $query->row();
	}
        
}

/* End of file sc_users.php */
/* Location: ./application/models/sc_users.php */
