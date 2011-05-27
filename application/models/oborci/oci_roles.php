<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Roles management model
 *
 * @author Anton Raharja
 */
class oci_roles extends MY_Model {

	protected $db_table = 'oci_roles';
        protected $db_has_many = array('oborci/oci_users' => 'role_id');

	function __construct() {
		parent::__construct();
	}

}

/* End of file oci_roles.php */
/* Location: ./application/models/oci_roles.php */
