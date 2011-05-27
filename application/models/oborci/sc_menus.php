<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Menus management model
 *
 * @author Anton Raharja
 */
class SC_menus extends MY_Model {

        public $id = NULL;
        public $module_id = NULL;
        public $parent = NULL;
        public $index = NULL;
        public $uri = NULL;
        public $text = NULL;
        public $title = NULL;
        public $id_css = NULL;

	protected $db_table = 'sc_menus';
        protected $db_fields = array('id', 'module_id', 'parent', 'index', 'uri', 'text', 'title', 'id_css');
        protected $db_key_field = 'id';

        function __construct() {
		parent::__construct();
	}

}

/* End of file sc_menus.php */
/* Location: ./application/models/sc_menus.php */
