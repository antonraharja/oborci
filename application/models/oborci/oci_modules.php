<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Modules management model
 *
 * @author Anton Raharja
 */
class oci_modules extends Oborci_Model {

	protected $db_table = 'oci_modules';

	function __construct() {
		parent::__construct();
	}

}

/* End of file oci_modules.php */
/* Location: ./application/models/oci_modules.php */
