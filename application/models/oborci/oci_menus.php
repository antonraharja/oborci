<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Menus management model
 *
 * @author Anton Raharja
 */
class oci_menus extends Oborci_Model {

	protected $db_table = 'oci_menus';

        function __construct() {
		parent::__construct();
	}

}

/* End of file oci_menus.php */
/* Location: ./application/models/oci_menus.php */
