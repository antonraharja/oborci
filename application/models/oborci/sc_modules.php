<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Modules management model
 *
 * @author Anton Raharja
 */
class SC_modules extends MY_Model {

	protected $db_table = 'sc_modules';

	function __construct() {
		parent::__construct();
	}

}

/* End of file sc_modules.php */
/* Location: ./application/models/sc_modules.php */
