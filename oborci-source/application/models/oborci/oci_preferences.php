<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Preferences management model
 *
 * @author Anton Raharja
 */
class oci_preferences extends Oborci_Model {

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

/* End of file oci_preferences.php */
/* Location: ./application/models/oci_preferences.php */

