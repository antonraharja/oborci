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

}

/* End of file oci_screens.php */
/* Location: ./application/models/oci_screens.php */
