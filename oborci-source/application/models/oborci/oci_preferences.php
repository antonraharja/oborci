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
            'oci_users' => array(
                'relation' => 'has_one',
                'key' => 'preferences',
            ),
        );

}

/* End of file oci_preferences.php */
/* Location: ./application/models/oci_preferences.php */

