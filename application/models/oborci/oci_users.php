<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Users management model
 *
 * @author Anton Raharja
 */
class oci_users extends MY_Model {

        protected $db_table = 'oci_users';

        function __construct() {
		parent::__construct();
	}

}

/* End of file oci_users.php */
/* Location: ./application/models/oci_users.php */
