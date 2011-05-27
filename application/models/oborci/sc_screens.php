<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Screens management model
 *
 * @author Anton Raharja
 */
class SC_screens extends MY_Model {

        protected $db_table = 'sc_screens';

	function __construct() {
		parent::__construct();
	}

	/**
	 * Get screen by URI
	 * @param integer $uri URI
	 * @return object Object of screen
	 */
	public function get_by_uri($uri) {
		$query = $this->db->get_where($this->db_table, array('uri' => $uri));
		return $query->row();
	}

}

/* End of file sc_screens.php */
/* Location: ./application/models/sc_screens.php */
