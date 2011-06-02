<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Users management model
 *
 * @author Anton Raharja
 */
class oci_users extends Oborci_Model {

	protected $db_table = 'oci_users';
        
        protected $db_fields = array(
            'id' => 'id',
            'role' => 'role_id',
            'preferences' => 'preference_id',
            'username' => 'username',
            'password' => 'password',
            'salt' => 'salt',
        );
        
        protected $db_primary_key = 'id';
        
        protected $db_relations = array(
            'oci_roles' => array(
                'relation' => 'belongs_to',
                'foreign_key' => 'role',
            ),
            'oci_preferences' => array(
                'relation' => 'belongs_to',
                'foreign_key' => 'preferences'
            ),
        );

}

/* End of file oci_users.php */
/* Location: ./application/models/oci_users.php */
