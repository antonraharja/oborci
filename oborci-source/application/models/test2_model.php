<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Test2 model
 *
 * @author Anton Raharja
 */
class Test2_Model extends Oborci_Model {

	protected $db_table = 'oci_roles';
        
        protected $db_fields = array(
            // map => field
            'id' => 'id',
            'name' => 'name',
        );
        
        protected $db_primary_key = 'id';
        
        protected $db_relations = array(
            // with oci_users we have has_many relation on (their) key 'role'
            // has_many: each of us have many of them
            'oci_users' => array(
                'relation' => 'has_many',
                'key' => 'role',
            ),
            // with oci_screens we have has_and_belongs_to_many relation on bridge key 'screen_id'
            // has_and_belongs_to_many: each of us have many of them through a join table
            'oci_screens' => array(
                'relation' => 'has_and_belongs_to_many',
                'join_table' => 'oci_roles_screens',
                'join_key' => 'role_id',
                'key' => 'screen_id',
            ),
            // with oci_menus we have has_and_belongs_to_many relation on bridge key 'menu_id'
            // has_and_belongs_to_many: each of us have many of them through a join table
            'oci_menus' => array(
                'relation' => 'has_and_belongs_to_many',
                'join_table' => 'oci_roles_menus',
                'join_key' => 'role_id',
                'key' => 'menu_id',
            ),
        );

}

/* End of file test2_model.php */
/* Location: ./application/models/test2_model.php */
