<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Screens management model
 *
 * @author Anton Raharja
 */
class SC_screens extends MY_Model {

	public $id = NULL;
        public $module_id = NULL;
        public $name = NULL;
        public $uri = NULL;
        
        protected $db_table = 'sc_screens';
        protected $db_fields = array('id', 'module_id', 'name', 'uri');
        protected $db_key_field = 'id';

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
