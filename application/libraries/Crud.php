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
	private $properties = NULL;
	private $key_field = NULL;
	private $CI = NULL;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->library(array('table', 'Form'));
	}

	/**
	 * Create insert or add button
	 * @return string $returns Insert or add button
	 */
	private function _button_insert() {
		$data = array(
			0 => array(
				'open' => array(
					'uri' => $this->properties['uri'],
					'name' => $this->properties['name'].'_button_insert',
				)
			),
			1 => array(
				'hidden' => array(
					'name' => 'crud_action',
					'value' => 'insert',
				),
			),
			2 => array(
				'submit' => array(
					'name' => 'crud_submit_insert',
					'value' => _('Add'),
				),
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
	 * @return string $returns Insert or add form
	 */
	private function _form_insert() {
		$data = array(
			0 => array(
				'open' => array(
					'uri' => $this->properties['uri'],
					'name' => $this->properties['name'].'_form_insert',
				),
			),
			1 => array(
				'hidden' => array(
					'name' => 'crud_action',
					'value' => 'insert_action',
				),
			),
		);
		foreach ($this->insert as $row) {
			$data[] = array(
				$row['type'] => $row
			);
			if ($row['confirm']) {
				$row['name'] = $row['name'].'_confirm';
				$row['label'] = $row['confirm_label'];
				$data[] = array(
					$row['type'] => $row
				);
			}
		}
		$data[] = array(
			'submit' => array(
				'name' => 'crud_submit_insert',
				'value' => _('Add')
			),
		);
		$this->CI->form->set_data($data);
		$returns = "<div id='crud_form_insert'>";
		$returns .= $this->CI->form->render();
		$returns .= "</div>";
		return $returns;
	}

	/**
	 * Create update or edit form
	 * @return string $returns Update or edit form
	 */
	private function _form_update() {
		$data = array(
			0 => array(
				'open' => array(
					'uri' => $this->properties['uri'],
					'name' => $this->properties['name'].'_form_update',
				),
			),
			1 => array(
				'hidden' => array(
					'name' => 'crud_action',
					'value' => 'update_action',
				),
			),
		);
		foreach ($this->update as $row) {
			$data[] = array(
				$row['type'] => $row
			);
			if ($row['confirm']) {
				$row['name'] = $row['name'].'_confirm';
				$row['label'] = $row['confirm_label'];
				$data[] = array(
					$row['type'] => $row
				);
			}
		}
		$data[] = array(
			'submit' => array(
				'name' => 'crud_submit_update',
				'value' => _('Edit')
			),
		);
		$this->CI->form->set_data($data);
		$returns = "<div id='crud_form_update'>";
		$returns .= $this->CI->form->render();
		$returns .= "</div>";
		return $returns;
	}

	/**
	 * Create delete or del form
	 * @return string $returns Delete or del form
	 */
	private function _form_delete() {
		$data = array(
			0 => array(
				'open' => array(
					'uri' => $this->properties['uri'],
					'name' => $this->properties['name'].'_form_delete',
				),
			),
			1 => array(
				'hidden' => array(
					'name' => 'crud_action',
					'value' => 'delete_action',
				),
			),
		);
		foreach ($this->delete as $row) {
			$data[] = array(
				$row['type'] => $row
			);
			if ($row['confirm']) {
				$row['name'] = $row['name'].'_confirm';
				$row['label'] = $row['confirm_label'];
				$data[] = array(
					$row['type'] => $row
				);
			}
		}
		$data[] = array(
			'submit' => array(
				'name' => 'crud_submit_delete',
				'value' => _('Del')
			),
		);
		$this->CI->form->set_data($data);
		$returns = "<div id='crud_form_delete'>";
		$returns .= $this->CI->form->render();
		$returns .= "</div>";
		return $returns;
	}

	/**
	 * Create checkbox on form
	 * @param string $key_value Value of key field
	 * @return string $returns Checkbox
	 */
	private function _checkbox($key_value) {
		$returns = "<div id='crud_checkbox'>";
		$returns .= $this->CI->form->checkbox(array('name' => $this->key_field.'[]', 'value' => $key_value));
		$returns .= "</div>";
		return $returns;
	}

	/**
	 * Create dropdown on form
	 * @return string $returns Dropdown
	 */
	private function _dropdown() {
		$options = array();
		if ($this->properties['update']) {
			$options1 = array('update' => _('Update'));
			$options = array_merge($options, $options1);
		}
		$options_delete = array();
		if ($this->properties['delete']) {
			$options1 = array('delete' => _('Delete'));
			$options = array_merge($options, $options1);
		}
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
	 * @return string $returns Grid
	 */
	private function _grid() {
		$column_size = NULL;
		$returns = NULL;
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
				$heading[] = $row['label']; // columns
				$select_fields[] = $row['name'];
				if (isset($row['key'])) {
					$this->key_field = $row['name'];
				}
			}
			
			if ($this->properties['update'] || $this->properties['delete']) {
				$column_size += 1;
				$heading[] = _('Action'); // last column
			}
		}
		
		if (count($select_fields) > 0) {	
			$returns = "<div id='crud_grid'>";
			$returns .= $this->CI->form->open(array('uri' => $this->properties['uri'], 'name' => $this->properties['name'].'_form'));
			
			$this->CI->table->set_heading($heading);
			
			$this->CI->db->select($select_fields);
			$query = $this->CI->db->get($this->properties['datasource']);
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
			$returns .= $this->CI->table->generate($new_list);

			if ($this->properties['update'] || $this->properties['delete']) {
				$returns .= $this->_dropdown();
				$returns .= $this->CI->form->submit(array( 'name' => 'crud_submit_form', 'value' => _('Go')));
			}
			$returns .= $this->CI->form->close();
			$returns .= "</div>";
		}
		
		return $returns;
	}

	/**
	 * Set uniquely formatted data structure
	 * Usage example: $this->crud->set_data($data);
	 * @param array $data Data array
	 * @return NULL
	 */
	public function set_data($data) {
		$this->data = $data;
		$this->insert = $data['insert'];
		$this->select = $data['select'];
		$this->update = $data['update'];
		$this->delete = $data['delete'];
		$this->properties = $data['properties'];
	}

	/**
	 * Render CRUD form and grid
	 * Usage example: return $this->crud->render();
	 * @return string $returns Forms and grid
	 */
	public function render() {
		$returns = NULL;
		$crud_action = trim(strtolower($this->CI->input->post('crud_action')));
		switch ($crud_action) {
			case 'insert':
				$returns .= $this->_form_insert();
				break;
			case 'insert_action':
				print_r($_POST);
				$returns .= "INSERTED";
				break;
			case 'update':
				$returns .= $this->_form_update();
				break;
			case 'update_action':
				print_r($_POST);
				$returns .= "UPDATED";
				break;
			case 'delete':
				$returns .= $this->_form_delete();
				break;
			case 'delete_action':
				print_r($_POST);
				$returns .= "DELETED";
				break;
			default:
				// grid
				$returns .= $this->_grid();
				// insert button
				if ($this->properties['insert']) {
					$returns .= $this->_button_insert();
				}
		}
		return $returns;
	}

}

/* End of file Crud.php */
