<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Roles management model
 *
 * @author Anton Raharja
 */
class oci_roles extends Oborci_Model {

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
            // with oci_screens we have has_many_through relation on bridge key 'screen_id'
            // has_many_through: each of us have many of them through a join table
            'oci_screens' => array(
                'relation' => 'has_many_through',
                'join_table' => 'oci_roles_screens',
                'join_key' => 'role_id',
                'key' => 'screen_id',
            ),
            // with oci_menus we have has_many_through relation on bridge key 'menu_id'
            // has_many_through: each of us have many of them through a join table
            'oci_menus' => array(
                'relation' => 'has_many_through',
                'join_table' => 'oci_roles_menus',
                'join_key' => 'role_id',
                'key' => 'menu_id',
            ),
        );

}

/* End of file oci_roles.php */
/* Location: ./application/models/oci_roles.php */
