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

}

/* End of file sc_users.php */
/* Location: ./application/models/sc_users.php */
