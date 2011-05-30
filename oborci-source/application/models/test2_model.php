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
            // with oci_roles we have has_one relation on foreign key 'role_id'
            // has_one: each of us have one of them
            'test_model' => array(
                'relation' => 'has_many',
                'key' => 'role',
            ),
            'oci_screens' => array(
                'relation' => 'has_many_through',
                'key' => 'role_id',
                'join_table' => 'oci_roles_screens',
                'join_key' => 'screen_id',
            ),
            'oci_menus' => array(
                'relation' => 'has_many_through',
                'key' => 'role_id',
                'join_table' => 'oci_roles_menus',
                'join_key' => 'menu_id',
            ),
        );

}

/* End of file test2_model.php */
/* Location: ./application/models/test2_model.php */
