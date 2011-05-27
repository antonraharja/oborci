<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Users management model
 *
 * @author Anton Raharja
 */
class SC_users extends MY_Model {

        protected $db_table = 'sc_users';

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
