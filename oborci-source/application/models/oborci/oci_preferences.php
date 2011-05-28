<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Preferences management model
 *
 * @author Anton Raharja
 */
class oci_preferences extends MY_Model {

	protected $db_table = 'oci_preferences';

	function __construct() {
		parent::__construct();
	}

}

/* End of file oci_preferences.php */
/* Location: ./application/models/oci_preferences.php */

