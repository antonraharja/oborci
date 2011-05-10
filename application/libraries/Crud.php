<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CRUD library
 * @author Anton Raharja
 *
 */
class Crud {

	private $data = NULL;
	// private $CI = NULL;

	function __construct() {
		$this->data = NULL;
		// $this->CI =& get_instance();
	}
	
	/**
	 * Set uniquely formatted data structure
	 * Usage example: $this->crud->set_data($data);
	 * @param array $data Data array
	 */
	public function set_data($data) {
			$this->data = $data;
	}

	/**
	 * Render CRUD form and grid
	 * Usage example: return $this->crud->render();
	 * @return NULL
	 */
	public function render() {
		return print_r($this->data, TRUE);
	}

}

/* End of file SC_CRUD.php */
