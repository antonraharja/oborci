<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CRUD library
 *
 * @author Anton Raharja
 */
class Crud {

	private $data = NULL;
	private $insert = NULL;
	private $select = NULL;
	private $update = NULL;
	private $delete = NULL;
	private $datasource = NULL;
	private $properties = NULL;
	private $key_field = NULL;
	private $CI = NULL;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('Form');
		$this->CI->load->library('table');
	}

	/**
	 * Create insert or add button
	 * @return string $data Insert or add button
	 */
	private function _button_insert() {
		$data = array(
			'open' => array(
				'uri' => $this->properties['uri'],
				'name' => $this->properties['name'].'_button_insert'
			),
			'hidden' => array(
				'name' => 'crud_action',
				'value' => 'insert',
			),
			'submit' => array(
				'name' => 'crud_submit_insert',
				'value' => _('Add')
			),
		);
		$this->CI->form->set_data($data);
		$returns = "<div id='crud_button_insert'>";
		$returns .= $this->CI->form->render();
		$returns .= "</div>";
		return $returns;
	}

	/**
	 * Create insert or add form
	 * @return string $data Insert or add form
	 */
	private function _form_insert() {
		return $data;
	}

	/**
	 * Create update or edit form
	 * @return string $data Update or edit form
	 */
	private function _form_update() {
		return $data;
	}

	/**
	 * Create delete or del form
	 * @return string $data Delete or del form
	 */
	private function _form_delete() {
		return $data;
	}

	/**
	 * Create checkbox on form
	 * @param string $key_value Value of key field
	 * @return string $data Checkbox
	 */
	private function _checkbox($key_value) {
		$returns = "<div id='crud_checkbox'>";
		$returns .= $this->CI->form->checkbox(array('name' => $this->key_field.'[]', 'value' => $key_value));
		$returns .= "</div>";
		return $returns;
	}

	/**
	 * Create dropdown on form
	 * @return string $data Dropdown
	 */
	private function _dropdown() {
		$options_update = array();
		if ($this->properties['update']) {
			$options_update = array('update' => _('Update'));
		}
		$options_delete = array();
		if ($this->properties['delete']) {
			$options_delete = array('delete' => _('Delete'));
		}
		$options = array_merge($options_update, $options_delete);
		$dropdown = array(
			'name' => 'crud_action', 
			'options' => $options
		);
		$returns = "<div id='crud_select'>";
		$returns .= $this->CI->form->dropdown($dropdown);
		$returns .= "</div>";
		return $returns;
	}

	/**
	 * Create grid
	 * @return string $data Grid
	 */
	private function _grid() {
		$column_size = NULL;
		$data = NULL;
		$heading = NULL;
		$select_fields = NULL;
		
		if ($this->properties['index_column']) {
			$column_size = 1;
			$index_column_count = $this->properties['index_column_start'];
			$heading[] = _('No'); // first column
		}
		
		$column_size += count($this->select);
		
		if (count($this->select) > 0) {
			foreach ($this->select as $row) {
				$heading[] = $row['title']; // columns
				$select_fields[] = $row['field'];
				if (isset($row['key'])) {
					$this->key_field = $row['field'];
				}
			}
			
			if ($this->properties['update'] || $this->properties['delete']) {
				$column_size += 1;
				$heading[] = _('Action'); // last column
			}
		}
		
		if (count($select_fields) > 0) {	
			$data = "<div id='crud_grid'>";
			$data .= $this->CI->form->open(array('uri' => $this->properties['uri'], 'name' => $this->properties['name'].'_form'));
			
			$this->CI->table->set_heading($heading);
			
			$this->CI->db->select($select_fields);
			$query = $this->CI->db->get($this->datasource['name']);
			$j =0;
			foreach ($query->result_array() as $row) {
				$j++;
				if ($this->properties['index_column']) {
					$list[] = $index_column_count++; // index column
				}
				for ($i=0;$i<count($select_fields);$i++) {
					$list[] = $row[$select_fields[$i]]; // data columns
				}
				if ($this->properties['update'] || $this->properties['delete']) {
					$list[] = $this->_checkbox('1'); // action column
				}
			}
			
			$new_list = $this->CI->table->make_columns($list, $column_size);
			$data .= $this->CI->table->generate($new_list);

			if ($this->properties['update'] || $this->properties['delete']) {
				$data .= $this->_dropdown();
				$data .= $this->CI->form->submit(array( 'name' => 'crud_submit_form', 'value' => _('Go')));
			}
			$data .= $this->CI->form->close();
			$data .= "</div>";
		}
		
		return $data;
	}

	/**
	 * Set uniquely formatted data structure
	 * Usage example: $this->crud->set_data($data);
	 * @param array $data Data array
	 */
	public function set_data($data) {
		$this->data = $data;
		$this->insert = $data['insert'];
		$this->select = $data['select'];
		$this->update = $data['update'];
		$this->delete = $data['delete'];
		$this->datasource = $data['datasource'];
		$this->properties = $data['properties'];
	}

	/**
	 * Render CRUD form and grid
	 * Usage example: return $this->crud->render();
	 * @return string $data Forms and grid
	 */
	public function render() {
		$data = NULL;
		$crud_action = trim(strtolower($this->CI->input->post('crud_action')));
		switch ($crud_action) {
			case 'insert':
				$data .= $this->_form_insert();
				break;
			case 'insert_action':
				$data .= "INSERTED";
				break;
			case 'update':
				$data .= $this->_form_update();
				break;
			case 'update_action':
				$data .= "UPDATED";
				break;
			case 'delete':
				$data .= $this->_form_delete();
				break;
			case 'delete_action':
				$data .= "DELETED";
				break;
			default:
				// grid
				$data .= $this->_grid();
				// insert button
				if ($this->properties['insert']) {
					$data .= $this->_button_insert();
				}
		}
		return $data;
	}

}

/* End of file Crud.php */
