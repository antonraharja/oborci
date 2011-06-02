<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Screens management model
 *
 * @author Anton Raharja
 */
class oci_screens extends Oborci_Model {

        protected $db_table = 'oci_screens';
        
        protected $db_fields = array(
            'id' => 'id',
            'module' => 'module_id',
            'name' => 'name',
            'uri' => 'uri'
        );
        
        protected $db_primary_key = 'id';
        
        protected $db_relations = array(
            'oci_roles' => array(
                'relation' => 'has_and_belongs_to_many',
                'join_table' => 'oci_roles_screens',
                'join_key' => 'screen_id',
                'key' => 'role_id',
            )
        );

}

/* End of file oci_screens.php */
/* Location: ./application/models/oci_screens.php */
