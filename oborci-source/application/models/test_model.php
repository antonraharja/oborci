<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Test model
 *
 * @author Anton Raharja
 */
class Test_Model extends Oborci_Model {

	protected $db_table = 'oci_users';
        
        protected $db_fields = array(
            // map => field
            'id' => 'id',
            'role' => 'role_id',
            'preferences' => 'preference_id',
            'username' => 'username',
            'password' => 'password',
            'salt' => 'salt',
        );
        
        protected $db_primary_key = 'id';
        
        protected $db_relations = array(
            // with oci_roles we have has_one relation on foreign key 'role_id'
            // has_one: each of us have one of them
            'oci_roles' => array(
                'relation' => 'has_one',
                'foreign_key' => 'role',
            ),
            'oci_preferences' => array(
                'relation' => 'has_one',
                'foreign_key' => 'preferences'
            ),
        );

}

/* End of file test_model.php */
/* Location: ./application/models/test_model.php */
