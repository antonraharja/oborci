<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Menus management model
 *
 * @author Anton Raharja
 */
class oci_menus extends Oborci_Model {

	protected $db_table = 'oci_menus';
        
        protected $db_fields = array(
            'id' => 'id',
            'module' => 'module_id',
            'parent' => 'parent',
            'index' => 'index',
            'uri' => 'uri',
            'text' => 'text',
            'title' => 'title',
            'id_css' => 'id_css'
        );

        protected $db_primary_key = 'id';

        protected $db_relations = array(
            'oci_roles' => array(
                'relation' => 'has_and_belongs_to_many',
                'join_table' => 'oci_roles_screens',
                'join_key' => 'menu_id',
                'key' => 'role_id',
            )
        );
        
}

/* End of file oci_menus.php */
/* Location: ./application/models/oci_menus.php */
