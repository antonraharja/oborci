<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Preferences management model
 *
 * @author Anton Raharja
 */
class SC_preferences extends MY_Model {

	protected $db_table = 'sc_preferences';

	function __construct() {
		parent::__construct();
	}

}

/* End of file sc_preferences.php */
/* Location: ./application/models/sc_preferences.php */

