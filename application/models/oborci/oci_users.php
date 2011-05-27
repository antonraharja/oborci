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
        protected $db_has_one = array(
            'oborci/oci_preferences' => 'preference_id',
            'oborci/oci_roles' => 'role_id',
            );

        function __construct() {
		parent::__construct();
	}

}

/* End of file oci_users.php */
/* Location: ./application/models/oci_users.php */
