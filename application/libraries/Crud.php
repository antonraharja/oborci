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
	private function _insert_button() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_insert_button');
		$this->CI->form->hidden('crud_action', 'insert');
		$this->CI->form->submit(_('Add'), 'crud_submit_insert');
		$data = "<div id='crud_insert_button'>";
		$data .= $this->CI->form->get();
		$data .= "</div>";
		$this->CI->form->clear();
		return $data;
	}

	/**
	 * Create update or edit button
	 * @return string $data Update or edit button
	 */
	private function _update_button() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_update_button');
		$this->CI->form->hidden('crud_action', 'update');
		$this->CI->form->submit(_('Edit'), 'crud_submit_update');
		$data = "<div id='crud_update_button'>";
		$data .= $this->CI->form->get();
		$data .= "</div>";
		$this->CI->form->clear();
		return $data;
	}

	/**
	 * Create delete or del button
	 * @return string $data Delete or del button
	 */
	private function _delete_button() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_delete_button');
		$this->CI->form->hidden('crud_action', 'delete');
		$this->CI->form->submit(_('Del'), 'crud_submit_delete');
		$data = "<div id='crud_delete_button'>";
		$data .= $this->CI->form->get();
		$data .= "</div>";
		$this->CI->form->clear();
		return $data;
	}

	/**
	 * Create insert or add form
	 * @return string $data Insert or add form
	 */
	private function _insert_form() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_insert_form');
		$this->CI->form->hidden('crud_action', 'insert_handle');
		$this->CI->form->submit(_('Submit'), 'crud_submit_insert');
		$data = "<div id='crud_insert_form'>";
		$data .= $this->CI->form->get();
		$data .= "</div>";
		$this->CI->form->clear();
		return $data;
	}

	/**
	 * Create update or edit form
	 * @return string $data Update or edit form
	 */
	private function _update_form() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_update_form');
		$this->CI->form->hidden('crud_action', 'update_handle');
		$this->CI->form->submit(_('Submit'), 'crud_submit_update');
		$data = "<div id='crud_update_form'>";
		$data .= $this->CI->form->get();
		$data .= "</div>";
		$this->CI->form->clear();
		return $data;
	}

	/**
	 * Create delete or del form
	 * @return string $data Delete or del form
	 */
	private function _delete_form() {
		$this->CI->form->open($this->properties['uri'], $this->properties['name'].'_delete_form');
		$this->CI->form->hidden('crud_action', 'delete_handle');
		$this->CI->form->submit(_('Submit'), 'crud_submit_delete');
		$data = "<div id='crud_delete_form'>";
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
			$heading[] = '*';
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
					$list[] = $this->_update_button() .' '.$this->_delete_button();
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
				$data .= $this->_insert_form();
				break;
			case 'insert_handle':
				$data .= "INSERTED";
				break;
			case 'update':
				$data .= $this->_update_form();
				break;
			case 'update_handle':
				$data .= "UPDATED";
				break;
			case 'delete':
				$data .= $this->_delete_form();
				break;
			case 'delete_handle':
				$data .= "DELETED";
				break;
			default:
				// insert button
				if ($this->properties['insert']) {
					$data .= $this->_insert_button();
				}
				// grid
				$data .= $this->_grid();
				// insert button
				if ($this->properties['insert']) {
					$data .= $this->_insert_button();
				}
		}
		return $data;
	}

}

/* End of file SC_CRUD.php */
