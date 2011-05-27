<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Preferences management model
 *
 * @author Anton Raharja
 */
class SC_preferences extends MY_Model {

        public $id = NULL;
        public $email = NULL;
        public $first_name = NULL;
        public $last_name = NULL;
        
	protected $db_table = 'sc_preferences';
        protected $db_fields = array('id', 'email', 'first_name', 'last_name');
        protected $db_key_field = 'id';

	function __construct() {
		parent::__construct();
	}

}

/* End of file sc_preferences.php */
/* Location: ./application/models/sc_preferences.php */

