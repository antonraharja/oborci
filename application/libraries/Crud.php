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
	private $CI = NULL;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('Form');
		$this->CI->load->library('table');
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
	 * Create insert or add button
	 * @return string $data Insert or add button
	 */
	private function _button_insert() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_button_insert');
		$this->CI->form->hidden('crud_action', 'insert');
		$this->CI->form->submit(_('Add'), 'crud_submit_insert');
		$data = "<div id='crud_button_insert'>";
		$data .= $this->CI->form->get();
		$data .= "</div>";
		$this->CI->form->clear();
		return $data;
	}

	/**
	 * Create update or edit button
	 * @return string $data Update or edit button
	 */
	private function _button_update() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_button_update');
		$this->CI->form->hidden('crud_action', 'update');
		$this->CI->form->submit(_('Edit'), 'crud_submit_update');
		$data = "<div id='crud_button_update'>";
		$data .= $this->CI->form->get();
		$data .= "</div>";
		$this->CI->form->clear();
		return $data;
	}

	/**
	 * Create delete or del button
	 * @return string $data Delete or del button
	 */
	private function _button_delete() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_button_delete');
		$this->CI->form->hidden('crud_action', 'delete');
		$this->CI->form->submit(_('Del'), 'crud_submit_delete');
		$data = "<div id='crud_button_delete'>";
		$data .= $this->CI->form->get();
		$data .= "</div>";
		$this->CI->form->clear();
		return $data;
	}

	/**
	 * Create insert or add form
	 * @return string $data Insert or add form
	 */
	private function _form_insert() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_form_insert');
		$this->CI->form->hidden('crud_action', 'insert_action');
		$this->CI->form->submit(_('Submit'), 'crud_submit_insert');
		$data = "<div id='crud_form_insert'>";
		$data .= $this->CI->form->get();
		$data .= "</div>";
		$this->CI->form->clear();
		return $data;
	}

	/**
	 * Create update or edit form
	 * @return string $data Update or edit form
	 */
	private function _form_update() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_form_update');
		$this->CI->form->hidden('crud_action', 'update_action');
		$this->CI->form->submit(_('Submit'), 'crud_submit_update');
		$data = "<div id='crud_form_update'>";
		$data .= $this->CI->form->get();
		$data .= "</div>";
		$this->CI->form->clear();
		return $data;
	}

	/**
	 * Create delete or del form
	 * @return string $data Delete or del form
	 */
	private function _form_delete() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_form_delete');
		$this->CI->form->hidden('crud_action', 'delete_action');
		$this->CI->form->submit(_('Submit'), 'crud_submit_delete');
		$data = "<div id='crud_form_delete'>";
		$data .= $this->CI->form->get();
		$data .= "</div>";
		$this->CI->form->clear();
		return $data;
	}

	/**
	 * Create grid
	 * @return string $data Grid
	 */
	private function _grid() {
		$data = NULL;
		$heading = NULL;
		$select_fields = NULL;
		if ($this->properties['index_column']) {
			$column_size = 1;
			$index_column_count = $this->properties['index_column_start'];
			$heading[] = _('No');
		}
		if (count($this->select) > 0) {
			foreach ($this->select as $row) {
				$heading[] = $row['title'];
				$select_fields[] = $row['field'];
			}
			if ($this->properties['update'] || $this->properties['delete']) {
				$column_size += 1;
				$heading[] = _('Action');
			}
			$data = "<div id='crud_grid'>";
			$this->CI->table->set_heading($heading);
			if ($this->datasource['source'] == 'table') {
				$this->CI->db->select($select_fields);
				$query = $this->CI->db->get($this->datasource['name']);
			} else {
				$query = $this->datasource['name'];
			}
			foreach ($query->result_array() as $row) {
				if ($this->properties['index_column']) {
					$list[] = $index_column_count++;
				}
				for ($i=0;$i<count($select_fields);$i++) {
					$list[] = $row[$select_fields[$i]];
				}
				if ($this->properties['update'] || $this->properties['delete']) {
					$list[] = $this->_button_update() .' '.$this->_button_delete();
				}
			}
			$column_size += count($this->select);
			$new_list = $this->CI->table->make_columns($list, $column_size);
			$data .= $this->CI->table->generate($new_list);
			$data .= "</div>";
		}
		return $data;
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

/* End of file SC_CRUD.php */
