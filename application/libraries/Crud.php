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
	private $fields = NULL;
	private $config = NULL;
	private $pagination = NULL;
	private $CI = NULL;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->library(array('table', 'pagination', 'Form'));
		$this->CI->load->config('crud', TRUE);
		$this->config = $this->CI->config->item('crud');
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
				),
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

		$returns = "<div id='crud_grid'>";
		$returns .= $this->properties['crud_title'];
		$returns .= $this->properties['insert_form_title'];
		$returns .= "<div id='crud_form_insert'>";
		$returns .= $this->CI->form->render();
		$returns .= "</div></div>";
		return $returns;
	}

	/**
	 * Create update or edit form
	 * @return string $returns Update or edit form
	 */
	private function _form_update() {
		$fields['select'] = array();
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
		$keys = $this->CI->input->post($this->key_field);
		$i = 0;
		foreach ($keys as $val) {
			$i++;
			$this->CI->db->select($this->fields['update']);
			$this->CI->db->where($this->key_field, $val);
			$query = $this->CI->db->get($this->properties['datasource']);
			foreach ($query->result_array() as $result) {
				foreach ($this->update as $row) {
					$original_name = $row['name'];
					$row['name'] = $original_name.'_'.$i;
					if ($row['show_value']) {
						$row['value'] = $result[$original_name];
					}
					$data[] = array(
						$row['type'] => $row
					);
					if ($row['confirm']) {
						$row['name'] = $original_name.'_confirm'.'_'.$i;
						$row['label'] = $row['confirm_label'];
						$data[] = array(
							$row['type'] => $row
						);
					}
					$data[] = array(
						'hidden' => array(
							'name' => $this->key_field.'_'.$i,
							'value' => $val,
						),
					);
				}
			}
		}
		$data[] = array(
			'submit' => array(
				'name' => 'crud_submit_update',
				'value' => _('Submit')
			),
		);
		$this->CI->form->set_data($data);

		$returns = "<div id='crud_grid'>";
		$returns .= $this->properties['crud_title'];
		$returns .= $this->properties['update_form_title'];
		$returns .= "<div id='crud_form_update'>";
		$returns .= $this->CI->form->render();
		$returns .= "</div></div>";
		return $returns;
	}

	/**
	 * Create delete or del form
	 * @return string $returns Delete or del form
	 */
	private function _form_delete() {
		$fields['select'] = array();
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
		$keys = $this->CI->input->post($this->key_field);
		$i = 0;
		foreach ($keys as $val) {
			$i++;
			$this->CI->db->select($this->fields['delete']);
			$this->CI->db->where($this->key_field, $val);
			$query = $this->CI->db->get($this->properties['datasource']);
			foreach ($query->result_array() as $result) {
				foreach ($this->delete as $row) {
					$row['type'] = 'input';
					$row['value'] = $result[$row['name']];
					$row['disabled'] = TRUE;
					$data[] = array(
						$row['type'] => $row
					);
					$data[] = array(
						'hidden' => array(
							'name' => $this->key_field.'[]',
							'value' => $val,
						),
					);
				}
			}
		}
		$data[] = array(
			'submit' => array(
				'name' => 'crud_submit_delete',
				'value' => _('Submit')
			),
		);
		$this->CI->form->set_data($data);

		$returns = "<div id='crud_grid'>";
		$returns .= $this->properties['crud_title'];
		$returns .= $this->properties['delete_form_title'];
		$returns .= "<div id='crud_form_delete'>";
		$returns .= $this->CI->form->render();
		$returns .= "</div></div>";
		return $returns;
	}

	/**
	 * Create insert or add action form
	 * @return string $returns Insert or add action form
	 */
	private function _form_insert_action() {
		$returns = "<div id='crud_grid'>";
		$returns .= $this->properties['crud_title'];
		$returns .= $this->properties['insert_form_title'];
		$returns .= "<div id='crud_form_insert'>";
		$returns .= print_r($_POST, TRUE);
		$returns .= "</div></div>";
		return $returns;
	}

	/**
	 * Create update or edit action form
	 * @return string $returns Update or edit action form
	 */
	private function _form_update_action() {
		$returns = "<div id='crud_grid'>";
		$returns .= $this->properties['crud_title'];
		$returns .= $this->properties['update_form_title'];
		$returns .= "<div id='crud_form_update'>";
		$returns .= print_r($_POST, TRUE);
		$returns .= "</div></div>";
		return $returns;
			}

	/**
	 * Create delete or del form
	 * @return string $returns Delete or del action form
	 */
	private function _form_delete_action() {
		$returns = "<div id='crud_grid'>";
		$returns .= $this->properties['crud_title'];
		$returns .= $this->properties['delete_form_title'];
		$returns .= "<div id='crud_form_delete'>";
		$returns .= print_r($_POST, TRUE);
		$returns .= "</div></div>";
		return $returns;
	}

	/**
	 * Create checkbox on form
	 * @param string $key_value Value of key field
	 * @return string $returns Checkbox
	 */
	private function _checkbox($key_value) {
		$data['name'] = $this->key_field.'[]';
		$data['id'] = $this->key_field;
		$data['value'] = $key_value;
		$returns .= $this->CI->form->checkbox($data);
		return $returns;
	}

	/**
	 * Generate javascript check all for checkbox
	 * @param string $id_main Checkbox check-all element ID
	 * @param string $id_child Checkbox child element ID
	 * @return string $returns Javascript
	 */
	private function _checkbox_checkall_js($id_main) {
		$returns = '
			<script type="text/javascript">
				$(document).ready(function() {
					$("#'.$id_main.'").click(function() {
						var checked_status = this.checked;
						$("#crud_grid input").each(function() {
							this.checked = checked_status;
						});
					});
				});
			</script>';
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
		$returns = NULL;
		$heading = NULL;
		$column_size = 0;
		
		// first column, index column
		if ($this->properties['index_column']) {
			$column_size = 1;
			$index_column_count = $this->properties['index_column_start'];
			$heading[] = array('data' => _('No'), 'id' => 'crud_th_index');
		}
		
		// data columns
		if (count($this->select) > 0) {
			foreach ($this->select as $row) {
				if (! $row['hidden']) {
					$column_size++;
					$heading[] = array('data' => $row['label'], 'id' => 'crud_th_'.$row['name']);
				}
			}
		}
		
		// last column, action column
		if ($this->properties['update'] || $this->properties['delete']) {
			$column_size += 1;
			$checkbox_id_main =  $this->key_field.'_main';
			$js_checkbox = $this->_checkbox_checkall_js($checkbox_id_main, $this->key_field);
			$data = array( 'name' => $checkbox_id_main);
			$heading[] = array('data' => $this->CI->form->checkbox($data), 'id' => 'crud_th_action');
		}

		// grid starts
		$returns = "<div id='crud_grid'>";
		$returns .= $js_checkbox;
		$returns .= $this->properties['crud_title'];
		$returns .= $this->properties['crud_form_title'];
		
		if (count($this->fields['select']) > 0) {
			
			// open form
			$returns .= $this->CI->form->open(array('uri' => $this->properties['uri'], 'name' => $this->properties['name'].'_form'));

			// set table heading
			$this->CI->table->set_heading($heading);

			// build table contents and push it to $list
			$this->CI->db->select($this->fields['select']);
			$this->CI->db->limit(
				$this->pagination['per_page'],
				$this->CI->uri->segment($this->CI->uri->total_segments()) * ($this->pagination['per_page'] - 1)
			);
			$query = $this->CI->db->get($this->properties['datasource']);
			$j =0;
			foreach ($query->result_array() as $row) {
				$j++;

				if ($this->properties['index_column']) {
					$list[] = $index_column_count++; // index column
				}

				for ($i=0;$i<count($this->fields['select']);$i++) {
					if (! $this->select[$i]['hidden']) {
						$list[] = $row[$this->fields['select'][$i]]; // data columns
					}
				}
				if ($this->properties['update'] || $this->properties['delete']) {
					$list[] = $this->_checkbox($row[$this->key_field]); // action column
				}
			}
			
			// generate table
			$new_list = $this->CI->table->make_columns($list, $column_size);
			$returns .= $this->CI->table->generate($new_list);
			
			// pagination
			$config = NULL;
			$config['base_url'] = base_url().'/'.$this->properties['uri'];
			$config['total_rows'] = $this->pagination['total_rows'];
			$config['per_page'] = $this->pagination['per_page'];
			$this->CI->pagination->initialize($config);
			$returns .= "<div id='crud_pagination'>";
			$returns .= $this->CI->pagination->create_links();
			$returns .= "</div>";

			// Update delete dropdown and Go button
			if ($this->properties['update'] || $this->properties['delete']) {
				$returns .= $this->_dropdown();
				$returns .= $this->CI->form->submit(array( 'name' => 'crud_submit_form', 'value' => _('Go')));
			}
			
			// close form
			$returns .= $this->CI->form->close();
		}
		
		// grid ends
		$returns .= "</div>";
		
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

		foreach ($this->select as $row) {
			$fields['select'][] = $row['name'];
			if (isset($row['key'])) {
				$this->key_field = $row['name'];
			}
		}
		$this->fields['select'] = $fields['select'];

		foreach ($this->update as $row) {
			$fields['update'][] = $row['name'];
		}
		$this->fields['update'] = $fields['update'];

		foreach ($this->delete as $row) {
			$fields['delete'][] = $row['name'];
		}
		$this->fields['delete'] = $fields['delete'];

		if (isset($this->properties['table_template'])) {
			$this->CI->table->set_template($this->properties['table_template']);
		} else {
			$this->CI->table->set_template($this->config['table_template']);
		}

		$this->pagination['total_rows'] = $this->CI->db->count_all($this->properties['datasource']);
		$this->pagination['per_page'] = isset($this->properties['pagination_per_page']) ? $this->properties['pagination_per_page'] : '10';
	}

	/**
	 * Render CRUD form and grid
	 * Usage example: return $this->crud->render();
	 * @return string $returns Forms and grid
	 */
	public function render() {
		$returns = NULL;
		
		// get crud_action, insert, update, delete or handle actions
		$crud_action = trim(strtolower($this->CI->input->post('crud_action')));
		switch ($crud_action) {
			case 'insert':
				$returns .= $this->_form_insert();
				return $returns;
				break;
			case 'insert_action':
				$returns .= $this->_form_insert_action();
				return $returns;
				break;
			case 'update':
				$keys = $this->CI->input->post($this->key_field);
				if (isset($keys[0])) {
					$returns .= $this->_form_update();
					return $returns;
				}
				break;
			case 'update_action':
				$returns .= $this->_form_update_action();
				break;
			case 'delete':
				$keys = $this->CI->input->post($this->key_field);
				if (isset($keys[0])) {
					$returns .= $this->_form_delete();
					return $returns;
				}
				break;
			case 'delete_action':
				$returns .= $this->_form_delete_action();
				break;
		}
		
		// grid
		$returns .= $this->_grid();
		// insert button
		if ($this->properties['insert']) {
			$returns .= $this->_button_insert();
		}
		
		return $returns;
	}

}

/* End of file Crud.php */
