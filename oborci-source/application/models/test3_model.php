<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Test3 model
 *
 * @author Anton Raharja
 */
class Test3_model extends Oborci_Model {

	protected $db_table = 'oci_preferences';

        protected $db_relations = array(
            // with oci_users we have belongs_to relation on (their) key 'preference_id'
            // belongs_to: each of us belongs_to one of them
            'oci_users' => array(
                'relation' => 'belongs_to',
                'key' => 'preferences',
            ),
        );

}

/* End of file test3_model.php */
/* Location: ./application/models/test3_model.php */

