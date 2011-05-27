<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Menus management model
 *
 * @author Anton Raharja
 */
class SC_menus extends MY_Model {

	protected $db_table = 'sc_menus';

        function __construct() {
		parent::__construct();
	}

}

/* End of file sc_menus.php */
/* Location: ./application/models/sc_menus.php */
