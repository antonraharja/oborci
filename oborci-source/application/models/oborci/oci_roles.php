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
            'id' => 'id',
            'name' => 'name',
        );
        
        protected $db_primary_key = 'id';
        
        protected $db_relations = array(
            'oci_users' => array(
                'relation' => 'has_many',
                'key' => 'role',
            ),
            'oci_screens' => array(
                'relation' => 'has_and_belongs_to_many',
                'join_table' => 'oci_roles_screens',
                'join_key' => 'role_id',
                'key' => 'screen_id',
            ),
            'oci_menus' => array(
                'relation' => 'has_and_belongs_to_many',
                'join_table' => 'oci_roles_menus',
                'join_key' => 'role_id',
                'key' => 'menu_id',
            ),
        );

}

/* End of file oci_roles.php */
/* Location: ./application/models/oci_roles.php */
