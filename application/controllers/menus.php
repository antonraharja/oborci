<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Menus controller
 *
 * @property SC_auth $SC_auth
 * @property SC_menus $SC_menus
 * @property crud $crud
 * @property template $template
 *
 * @author Anton Raharja
 *
 */
class Menus extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('speedcoding/SC_menus'));
	}

	/**
	 * Test
	 */
	public function test() {
                $this->SC_menus->text = 'Test Text';
                $this->SC_menus->title = 'Test Title';
                $id = $this->SC_menus->insert();
                $query = $this->SC_menus->get($id);
                foreach ($query->result_array() as $row) {
                        print_r($row);
                }
                $this->SC_menus->delete($id);
	}
	
}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */
