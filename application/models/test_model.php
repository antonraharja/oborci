<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * test management model
 *
 * @author Anton Raharja
 */
class Test_Model extends Oborci_Model {

	protected $db_table = 'oci_roles';
        protected $db_has_many = array(
            'users' => array('oborci/oci_users' => 'role_id')
        );

}

/* End of file test_model.php */
/* Location: ./application/models/test_model.php */
