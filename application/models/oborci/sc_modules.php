<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Modules management model
 *
 * @author Anton Raharja
 */
class SC_modules extends MY_Model {

        public $id = NULL;
        public $path = NULL;
        public $name = NULL;
        public $status = NULL;
        
	protected $db_table = 'sc_modules';
        protected $db_fields = array('id', 'path', 'name', 'status');
        protected $db_key_field = 'id';

	function __construct() {
		parent::__construct();
	}

}

/* End of file sc_modules.php */
/* Location: ./application/models/sc_modules.php */
