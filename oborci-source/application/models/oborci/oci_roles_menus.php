<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Roles management model
 *
 * @author Anton Raharja
 */
class oci_roles_menus extends MY_Model {

	protected $db_table = 'oci_roles_menus';

	function __construct() {
		parent::__construct();
	}

}

/* End of file oci_roles_menus.php */
/* Location: ./application/models/oci_roles_menus.php */