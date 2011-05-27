<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CRUD library for CodeIgniter
 *
 * @author Anton Raharja
 * @version 0.9
 * @see http://github.com/antonraharja/oborci
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
	private $fields = NULL;
	private $config = NULL;
	private $pagination = NULL;
	private $flashdata = NULL;
	private $CI = NULL;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->library(array('table', 'pagination', 'oborci/Form'));
	}

	/**
	 * Create insert or add button
	 * @return string $returns Insert or add button
	 */
	private function _button_insert() {
		$data = array(
			array('open' => array('uri' => $this->properties['uri'], 'name' => $this->properties['name'].'_button_insert'),),
			array('hidden' => array('name' => 'crud_action', 'value' => 'insert'),),
			array('submit' => array('name' => 'crud_submit_insert', 'value' => t('Add')),),
		);
                $this->CI->form->init();
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
		$flashdata = $this->flashdata;
		$data = array(
			array('open' => array('uri' => $this->properties['uri'], 'name' => $this->properties['name'].'_form_insert')),
			array('hidden' => array('name' => 'crud_action', 'value' => 'insert_action')));
		foreach ($this->insert as $row) {
			$row['value'] = $flashdata['inputs'][$row['name']];
        		// if password then value is NULLed
			if ($row['type']=='password') {
				unset($row['value']);
			}
			$data[] = array(
				$row['type'] => $row
			);
			if ($row['confirm']) {
				$row['name'] = $row['name'].'_confirm';
				$row['label'] = $row['confirm_label'];
				unset($row['value']);
				$data[] = array($row['type'] => $row);
			}
		}
		$data[] = array('submit' => array('name' => 'crud_submit_insert', 'value' => t('Add')));
                
                $returns = $this->_get_form('insert', $data);
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
		$returns .= $this->_form_insert_process();
		$returns .= "</div></div>";
		return $returns;
	}
	
	/**
	 * Process inputs on insert form
	 * @return string HTML returns on process
	 */
	private function  _form_insert_process() {
		$returns = NULL;
		$error = NULL;
		
		// get valid inputs
		$data = $this->_get_data_by_name('insert');
		$inputs = $this->_get_valid_inputs('insert');
		
		// set flashdata containing current inputs, incase we need it (on error for instance)
		$flashdata['crud_flashdata']['crud_action'] = 'insert';
		$flashdata['crud_flashdata']['inputs'] = $inputs;
		
		// process answers or inputs
		foreach ($inputs as $key => $val) {
			// apply functions
			if (isset($data_select[$key]['apply_function'])) {
				foreach ($data_select[$key]['apply_function'] as $i => $function) {
					$inputs[$key] = call_user_func($function, $val);
				}
			}
			// check if the field is unique
			if ($data[$key]['unique']) {
				$query = $this->CI->db->get_where($this->datasource['table'], array( $key => $val ));
				if ($query->num_rows()) {
					$error[$key] = t('data is already exists');
				}
			}
			// check if the field need to be confirmed, password for instance
			if ($data[$key]['confirm']) {
				$confirm_val = $this->CI->input->post($key.'_confirm');
				if ($confirm_val != $val) {
					$error[$key] = t('confirmation answer is different');
				}
			}
			// check if the field is required 
			if ($data[$key]['required']) {
				if (empty($val)) {
					$error[$key] = t('you must fill this field');
				}
			}
			// check if the field is disabled or readonly, unset the inputs if it is
			if ($data[$key]['disabled'] || $data[$key]['readonly']) {
				unset($inputs[$key]);
			}
		}
		
		// determine to returns error messages or success messages
		if (count($error) > 0) {
			$error_string = NULL;
			foreach ($error as $key1 => $val1) {
				$error_string .= '<p id="message_error">'.$key1.' - '.$val1.'</p>';
			}
			$returns .= $error_string;
			
			// set flashdata to session, this data will be used to re-entry the input value
			$this->CI->session->set_userdata($flashdata);
			
		} else {
			$result = $this->CI->db->insert($this->datasource['table'], $inputs);
			if ($result) { 
				$returns .= '<p id="message_success">'.t('data has been saved').'</p>';
			} else {
				$returns .= '<p id="message_error">'.t('fail to save data').'</p>';
			}
		}
		
		// always add back button
		$returns .= anchor($this->properties['uri'], t('Back'));
		
		return $returns;
	}
	
	/**
	 * Create update or edit form
	 * @return string $returns Update or edit form
	 */
	private function _form_update() {
		$flashdata = $this->flashdata;
		$data = array(
			array('open' => array('uri' => $this->properties['uri'], 'name' => $this->properties['name'].'_form_update')),
			array('hidden' => array('name' => 'crud_action', 'value' => 'update_action')));
		if (isset($flashdata['inputs'][0][$this->key_field])) {
			foreach ($flashdata['inputs'] as $row) {
				$keys[] = $row[$this->key_field];
			}
		} else {
			$keys = $this->CI->input->post($this->key_field);
		}
		$i = 0;
		foreach ($keys as $val) {
			$data[] = array('input' => array('name' => $this->key_field.'__', 'label' => $this->key_field, 'value' => $val, 'readonly' => TRUE));
			$this->CI->db->select($this->fields['update']);
			$this->CI->db->where($this->key_field, $val);
			$query = $this->CI->db->get($this->datasource['table']);
			foreach ($query->result_array() as $result) {
				foreach ($this->update as $row) {
					$original_name = $row['name'];
					$row['name'] = $original_name.'_'.$i;
					$row['value'] = $result[$original_name];
					// if dropdown then selected is value
					if ($row['type']=='dropdown') {
						$row['selected'] = $row['value'];
					}
					// if password then value is NULLed
					if ($row['type']=='password') {
						unset($row['value']);
					}
					// handle disabled, change it to readonly
					if (isset($row['disabled'])) {
						$row['readonly'] = $row['disabled'];
						unset($row['disabled']);
					}
					$data[] = array(
						$row['type'] => $row
					);
					// handle confirm
					if (($row['type']=='input' || $row['type']=='password') && $row['confirm']) {
						$row['name'] = $original_name.'_confirm'.'_'.$i;
						$row['label'] = $row['confirm_label'];
						$data[] = array($row['type'] => $row);
					}
					// IDs
					$data[] = array('hidden' => array('name' => $this->key_field.'_'.$i, 'value' => $val));
					$data[] = array('hidden' => array('name' => $this->key_field.'[]', 'value' => $val));
				}
			}
			$i++;
		}
		$data[] = array('hidden' => array('name' => 'field_num', 'value' => $i));
		$data[] = array('submit' => array('name' => 'crud_submit_update', 'value' => t('Submit')));

                $returns = $this->_get_form('update', $data);
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
		$returns .= $this->_form_update_process();
		$returns .= "</div></div>";
		return $returns;
	}

	/**
	 * Process inputs on update form
	 * @return string HTML returns on process
	 */
	private function  _form_update_process() {
		$returns = NULL;
		$error = NULL;
		
		// get valid inputs
		$data = $this->_get_data_by_name('update');
		$inputs = $this->_get_valid_inputs('update');
		
		// set flashdata containing current inputs, incase we need it (on error for instance)
		$flashdata['crud_flashdata']['crud_action'] = 'update';
		$flashdata['crud_flashdata']['inputs'] = $inputs;
		
		// process answers or inputs
		foreach ($inputs as $block_key => $block_val) {
			foreach ($block_val as $key => $val) {
				if ($key != $this->key_field) {
					// apply functions
					if (isset($data_select[$key]['apply_function'])) {
						foreach ($data_select[$key]['apply_function'] as $i => $function) {
							$inputs[$block_key][$key] = call_user_func($function, $val);
						}
					}
					// check if the field is unique
					if ($data[$key]['unique']) {
						$query = $this->CI->db->get_where($this->datasource['table'], array( $key => $val ));
						if ($query->num_rows()) {
							$error[$block_key][$key] = t('data is already exists');
						}
					}
					// check if the field need to be confirmed, password for instance
					if ($data[$key]['confirm']) {
						$confirm_val = $this->CI->input->post($key.'_confirm_'.$block_key);
						if ($confirm_val != $val) {
							$error[$block_key][$key] = t('confirmation answer is different');
						}
					}
					// check if the field is required 
					if ($data[$key]['required']) {
						if (empty($val)) {
							$error[$block_key][$key] = t('you must fill this field');
						}
					} else {
						if (empty($val) && $data[$key]['type']=='password') {
							unset($inputs[$block_key][$key]);
						}
					}
					// check if the field is disabled or readonly, unset the inputs if it is
					if ($data[$block_key][$key]['disabled'] || $data[$block_key][$key]['readonly']) {
						unset($inputs[$block_key][$key]);
					}
				}
			}
		}
		
		// determine to returns error messages or success messages
		$error_exists = FALSE;
		foreach ($inputs as $block_key => $block_val) {
			$key_field_val = $this->CI->input->post($this->key_field.'_'.$block_key);
			if (count($error[$block_key]) > 0) {
				$error_string = NULL;
				foreach ($error[$block_key] as $key1 => $val1) {
					$error_string .= '<p id="message_error">'.strtoupper($this->key_field).':'.$key_field_val.' - '.$key1.' - '.$val1.'</p>';
				}
				$returns .= $error_string;
				$error_exists = TRUE;
			} else {
				$result = $this->CI->db->update($this->datasource['table'], $block_val, array($this->key_field => $key_field_val));
				if ($result) { 
					unset($flashdata['crud_flashdata']['inputs'][$block_key]);
					$returns .= '<p id="message_success">'.strtoupper($this->key_field).':'.$key_field_val.' - '.t('data has been saved').'</p>';
				} else {
					$returns .= '<p id="message_error">'.strtoupper($this->key_field).':'.$key_field_val.' - '.t('fail to save data').'</p>';
					$error_exists = TRUE;
				}
			}
		}

		if ($error_exists) {
			// set flashdata to session, this data will be used to re-entry the input value
			sort($flashdata['crud_flashdata']['inputs']);
			$this->CI->session->set_userdata($flashdata);
		}
		
		// always add back button
		$returns .= anchor($this->properties['uri'], t('Back'));
		
		return $returns;
	}
	
	/**
	 * Create delete or del form
	 * @return string $returns Delete or del form
	 */
	private function _form_delete() {
		$flashdata = $this->flashdata;
		$data = array(
			array('open' => array('uri' => $this->properties['uri'], 'name' => $this->properties['name'].'_form_delete')),
			array('hidden' => array('name' => 'crud_action', 'value' => 'delete_action')));
		if (isset($flashdata['inputs'][0][$this->key_field])) {
			foreach ($flashdata['inputs'] as $row) {
				$keys[] = $row[$this->key_field];
			}
		} else {
			$keys = $this->CI->input->post($this->key_field);
		}
		$i = 0;
		foreach ($keys as $val) {
			$data[] = array('input' => array('name' => $this->key_field.'__', 'label' => $this->key_field, 'value' => $val, 'readonly' => TRUE));
			$this->CI->db->select($this->fields['delete']);
			$this->CI->db->where($this->key_field, $val);
			$query = $this->CI->db->get($this->datasource['table']);
			foreach ($query->result_array() as $result) {
				foreach ($this->delete as $row) {
					$row['type'] = 'input';
					$row['value'] = $result[$row['name']];
					// if dropdown then selected is value
					if ($row['type']=='dropdown') {
						$row['selected'] = $row['value'];
					}
					// if password then value is NULLed
					if ($row['type']=='password') {
						$row['value'] = NULL;
					}
					unset($row['disabled']);
					$row['readonly'] = TRUE;
					$data[] = array(
						$row['type'] => $row
					);
					$data[] = array('hidden' => array('name' => $this->key_field.'_'.$i, 'value' => $val));
					$data[] = array('hidden' => array('name' => $this->key_field.'[]', 'value' => $val));
				}
			}
			$i++;
		}
		$data[] = array('hidden' => array('name' => 'field_num', 'value' => $i));
		$data[] = array('submit' => array('name' => 'crud_submit_delete', 'value' => t('Submit')));
                
                $returns = $this->_get_form('delete', $data);
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
		$returns .= $this->_form_delete_process();
		$returns .= "</div></div>";
		return $returns;
	}

	/**
	 * Process inputs on delete form
	 * @return string HTML returns on process
	 */
	private function  _form_delete_process() {
		$returns = NULL;
		$error = NULL;
		
		// get valid inputs
		$data = $this->_get_data_by_name('delete');
		$inputs = $this->_get_valid_inputs('delete');
		
		// set flashdata containing current inputs, incase we need it (on error for instance)
		$flashdata['crud_flashdata']['crud_action'] = 'delete';
		$flashdata['crud_flashdata']['inputs'] = $inputs;

		// process answers or inputs
		// determine to returns error messages or success messages
		$error_exists = FALSE;
		foreach ($inputs as $block_key => $block_val) {
			$key_field_val = $this->CI->input->post($this->key_field.'_'.$block_key);
			$result = $this->CI->db->delete($this->datasource['table'], array($this->key_field => $key_field_val));
			if ($result) { 
				unset($flashdata['crud_flashdata']['inputs'][$block_key]);
				$returns .= '<p id="message_success">'.$this->key_field.':'.$key_field_val.' - '.t('data has been deleted').'</p>';
			} else {
				$returns .= '<p id="message_error">'.$this->key_field.':'.$key_field_val.' - '.t('fail to delete data').'</p>';
				$error_exists = TRUE;
			}
		}

		if ($error_exists) {
			// set flashdata to session, this data will be used to re-entry the input value
			sort($flashdata['crud_flashdata']['inputs']);
			$this->CI->session->set_userdata($flashdata);
		}
		
		// always add back button
		$returns .= anchor($this->properties['uri'], t('Back'));
		
		return $returns;
	}
	
        /**
         * Generate a search box
         * @return string Search box
         */
        private function _form_search_box() {
                $this->CI->form->init();
                $this->CI->form->set_name('search');
                $this->CI->form->set_uri($this->properties['uri']);
                $this->CI->form->open();
                $this->CI->form->hidden(array('name' => 'crud_action', 'value' => 'search'));
                $this->CI->form->dropdown(array('name' => 'form_search_field', 'options' => $this->fields['search'], 'selected' => $this->CI->input->post('form_search_field')));
                $this->CI->form->input(array('name' => 'form_search_content', 'value' => $this->CI->input->post('form_search_content')));
                $this->CI->form->submit(array('value' => 'Search'));
                $this->CI->form->close();
                $form = $this->CI->form->render();
                $returns = '<div id="form_search_box">';
                $returns .= $form;
                $returns .= '</div>';
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
	private function _load_js($id_main, $action_index) {
                if (! $this->pagination['status']) {
                        $tablesorter_pager = '.tablesorterPager({container: $("#pagination")})'; 
                }
                $returns = '
			<script type="text/javascript">
				$(document).ready(function() {
					$("#form_'.$id_main.'").click(function() {
						var checked_status = this.checked;
						$("#crud_grid input").each(function() {
							this.checked = checked_status;
						});
					});
                                        $("#crud_table")
                                                .tablesorter({ 
                                                        // pass the headers argument and assing a object 
                                                        headers: { 
                                                                // assign the secound column (we start counting zero) 
                                                                '.$action_index.': { 
                                                                        // disable it by setting the property sorter to false 
                                                                        sorter: false 
                                                                }, 
                                                        }
                                                })
                                                '.$tablesorter_pager.';
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
			$options += array('update' => t('Update'));
		}
		$options_delete = array();
		if ($this->properties['delete']) {
			$options += array('delete' => t('Delete'));
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
         * Helper function to get grid query and its total row
         * @return array Array query and its total row
         */
        private function _grid_query() {
		// TODO if only I know the better way...
		
		// build table contents and push it to $list
		$this->CI->db->select($this->fields['select']);
		if (isset($this->datasource['where'])) {
			$this->CI->db->where($this->datasource['where']);
		}
		// handle relation with join options
		if (isset($this->datasource['join_table'])) {
			if (isset($this->datasource['join_type'])) { 
				$this->CI->db->join($this->datasource['join_table'], $this->datasource['join_param'], $this->datasource['join_type']);
			} else {
				$this->CI->db->join($this->datasource['join_table'], $this->datasource['join_param']);
			}
		}
                
                // search box related
                $crud_action = $this->CI->input->post('crud_action');
                if ($crud_action == 'search') {
                        $field = $this->CI->input->post('form_search_field');
                        $content = $this->CI->input->post('form_search_content');
                        $this->CI->db->like($field, $content);
                }
                
		$query = $this->CI->db->get($this->datasource['table']);
                
		$total_rows = $query->num_rows();
                
                if ($this->pagination['status']) {
                        $this->CI->db->flush_cache();

                        // build table contents and push it to $list
                        $this->CI->db->select($this->fields['select']);
                        if (isset($this->datasource['where'])) {
                                $this->CI->db->where($this->datasource['where']);
                        }
                        // handle relation with join options
                        if (isset($this->datasource['join_table'])) {
                                if (isset($this->datasource['join_type'])) { 
                                        $this->CI->db->join($this->datasource['join_table'], $this->datasource['join_param'], $this->datasource['join_type']);
                                } else {
                                        $this->CI->db->join($this->datasource['join_table'], $this->datasource['join_param']);
                                }
                        }
                        // add limit for pagination
                        $this->CI->db->limit($this->pagination['per_page'], $this->_get_pagination_offset());

                        // search box related
                        $crud_action = $this->CI->input->post('crud_action');
                        if ($crud_action == 'search') {
                                $field = $this->CI->input->post('form_search_field');
                                $content = $this->CI->input->post('form_search_content');
                                $this->CI->db->like($field, $content);
                        }

                        $query = $this->CI->db->get($this->datasource['table']);
                }
                
		return array($query, $total_rows);
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
			$index_column_count = $this->properties['index_column_start'] + $this->_get_pagination_offset();
			$heading[] = array('data' => t('No'), 'id' => 'crud_th_index');
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
			$action_index = $column_size - 1;
			$checkbox_id_main =  $this->key_field.'_main';
			$data = array( 'name' => $checkbox_id_main);
			$heading[] = array('data' => $this->CI->form->checkbox($data), 'id' => 'crud_th_action');
		}

                // load javascript for grid table sorter
                $action_index = isset($action_index) ? $action_index : 100;
                $returns = $this->_load_js($checkbox_id_main, $action_index);
                
		// grid starts
		$returns .= "<div id='crud_grid'>";
		$returns .= $this->properties['crud_title'];
		$returns .= $this->properties['crud_form_title'];
		
		if (count($this->fields['select']) > 0) {
			
                        // get a search box
                        if (is_array($this->search)) {
                                $returns .= $this->_form_search_box();
                        }
                        
			// insert button
			if ($this->properties['insert']) {
				$returns .= $this->_button_insert();
			}
			
                        // init form
                        $this->CI->form->init();
                        
			// open form
			$returns .= $this->CI->form->open(array('uri' => $this->properties['uri'], 'name' => $this->properties['name']));

			// set table heading
			$this->CI->table->set_heading($heading);
			
			// get query results and total number of rows
			list($query, $this->pagination['total_rows']) = $this->_grid_query();
			$j =0;
			foreach ($query->result_array() as $row) {
				$j++;
				if ($this->properties['index_column']) {
					$list[] = array('data' => $index_column_count++, 'id' => 'crud_td_index'); // index column
				}
				
				$data_select = $this->_get_data_by_name('select');
				foreach ($row as $key => $val) {
					// apply functions
					if (isset($data_select[$key]['apply_function'])) {
						foreach ($data_select[$key]['apply_function'] as $i => $function) {
                                                        if (function_exists($function)) {
                                                                $row[$key] = call_user_func($function, $row[$key]);
                                                        }
						}
					}
					// if has link option then parse the link and set an anchor
					if (isset($data_select[$key]['link'])) {
						$parsed_link = $this->_parse_patterns($data_select[$key]['link'], $row);
						$row[$key] = anchor($parsed_link, $row[$key], 'title="'.$row[$key].'"');
					}
					// if not set hidden then show it
					if (! $data_select[$key]['hidden']) {
						$list[] = array('data' => $row[$key], 'id' => 'crud_td_'.$key); // data columns
					}
				}
				
				if ($this->properties['update'] || $this->properties['delete']) {
					$list[] = array('data' => $this->_checkbox($row[$this->key_field]), 'id' => 'crud_td_action'); // action column
				}
			}
			
			// generate table
			$new_list = $this->CI->table->make_columns($list, $column_size);
			$returns .= $this->CI->table->generate($new_list);
			
			// pagination
                        if ($this->pagination['status']) {
                                $returns .= $this->_get_pagination();
                        } else {
                                $returns .= '
                                        <div id="pagination" class="pagination">
                                                <form>
                                                        <img src="'.base_url().'assets/images/first.png" class="first"/>
                                                        <img src="'.base_url().'assets/images/prev.png" class="prev"/>
                                                        <input type="text" class="pagedisplay"/>
                                                        <img src="'.base_url().'assets/images/next.png" class="next"/>
                                                        <img src="'.base_url().'assets/images/last.png" class="last"/>
                                                        <select class="pagesize">
                                                                <option selected="selected" value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="30">30</option>
                                                                <option value="40">40</option>
                                                                <option value="40">50</option>
                                                        </select>
                                                </form>
                                        </div>';
                        }

			// Update delete dropdown and Go button
			if ($this->properties['update'] || $this->properties['delete']) {
				$returns .= $this->CI->form->submit(array( 'name' => 'crud_submit', 'value' => t('Go')));
				$returns .= $this->_dropdown();
			}
			
			// close form
			$returns .= $this->CI->form->close();
		}
		
		// grid ends
                $returns .= "</div>";
		
		return $returns;
	}
	
	/**
	 * Get pagination offset, used by query limit and index column start
	 * @return number $returns Offset
	 */
	private function _get_pagination_offset() {
		$page = (integer) $this->CI->uri->segment($this->CI->uri->total_segments());
		if ($this->properties['uri'] == $this->CI->uri->uri_string()) {
			$page = 0;
		}
		$returns = $page;
		return $returns;
	}
	
        /**
         * Get pagination
         * @return string HTML of pagination
         */
        private function _get_pagination() {
                $returns = NULL;
                $config['base_url'] = base_url().$this->properties['uri'];
                $config['total_rows'] = $this->pagination['total_rows'];
                $config['per_page'] = $this->pagination['per_page'];
                
                // if the last segment is number then do a little trick
                // CI pagination lib is not detecting uri_segment properly
                $last = (integer) substr($config['base_url'], -1, 1);
                if ($last > 0) {
                        $uri_segment = $this->CI->uri->total_segments() - 1;
                } else {
                        $uri_segment = $this->CI->uri->total_segments();
                }
                $config['uri_segment'] = $uri_segment;
                
                $this->CI->pagination->initialize($config);
                $returns .= "<div id='crud_pagination'>";
                $returns .= $this->CI->pagination->create_links();
                $returns .= "</div>";
                return $returns;
        }
        
	/**
	 * Get valid inputs from POST inputs
	 * @param string $action Action insert, select, update or delete
	 * @return array Array name-value pair of input variable and its value
	 */
	private function _get_valid_inputs($action) {
		$data = NULL;
		switch ($action) {
			case 'insert':
				foreach ($this->fields[$action] as $field) {
					$val = $this->CI->input->post($field);
					$data[$field] = $val;
				}
				$data[$this->key_field] = $this->CI->input->post($this->key_field);
				break;
			case 'update':		
			case 'delete':		
				$field_num = $this->CI->input->post('field_num');
				for ($i=0;$i<$field_num;$i++) {
					foreach ($this->fields[$action] as $field) {
						$val = $this->CI->input->post($field.'_'.$i);
						$data[$i][$field] = $val;
					}
					$data[$i][$this->key_field] = $this->CI->input->post($this->key_field.'_'.$i);
				}
				break;
		}
		return $data;
	}
	
	/**
	 * Get data as array of name instead of index
	 * @param string $action Action insert, select, update or delete
	 * @return array Array of data by name
	 */
	private function _get_data_by_name($action) {
		$returns = NULL;
		$data = $this->data[$action];
		foreach ($data as $row) {
			$name = $row['name'];
			unset($row['name']);
			$returns[$name] = $row;
		}
		return $returns;
	}
	
	/**
	 * Get valid crud options, mainly breakdown rules options
	 * @param array $data Data set in the controller
	 * @return array $returns Validated data
	 */
	private function _get_crud_options($data) {
		$returns = NULL;
		$array_block = array('insert', 'select', 'update', 'delete');
		$array_rules = array('unique', 'required', 'readonly', 'disabled', 'confirm', 'key', 'hidden');
		foreach ($data as $block_key => $block_val) {
			if (in_array($block_key, $array_block)) {
				foreach ($block_val as $row_key => $row_val) {
					foreach ($row_val as $option_key => $option_val) {
						if ($option_key == 'rules') {
							foreach ($option_val as $rules_key => $rules_val) {
                                                                if (is_array($rules_val)) {
                                                                        foreach ($rules_val as $sub_rules_key => $sub_rules_val) {
                                                                                $returns[$block_key][$row_key][$sub_rules_key] = $sub_rules_val;
                                                                        }
                                                                } else {
                                                                        if (in_array($rules_val, $array_rules)) {
                                                                                $returns[$block_key][$row_key][$rules_val] = TRUE;
                                                                        } else {
                                                                                $returns[$block_key][$row_key]['apply_function'][] = $rules_val;
                                                                        }
                                                                }
							}
						} else {
							$returns[$block_key][$row_key][$option_key] = $option_val;
						}
					}
				}
			} else {
				$returns[$block_key] = $block_val;
			}
		}
		return $returns;
	}

        /**
         * Get form on insert, update and delete
         * @param string $action Action insert, update or delete
         * @param array $data Form data array
         * @return string HTML form
         */
        private function _get_form($action, $data) {
                $this->CI->form->init();
                $this->CI->form->set_data($data);
                $form .= $this->CI->form->render();
                $returns = "<div id='crud_grid'>";
                $returns .= $this->properties['crud_title'];
                $returns .= $this->properties[$action.'_form_title'];
                $returns .= "<div id='crud_form_".$action."'>";
                $returns .= $form;
                $returns .= "</div></div>";
                return $returns;
        }
        
	/**
	 * Parsed specially formatted text. For example, the function will replace: preference/{id}
	 * @param string $unparsed Source unparsed string
	 * @param array $pair Key-Value pair array where part of unparsed string matched with the key and will be replaced by the value
	 * @return $parsed Parsed string
	 */
	private function _parse_patterns($unparsed, $pair) {
		$parsed = $unparsed;
		foreach ($pair as $key => $val) {
			$parsed = str_ireplace('{'.$key.'}', $val, $parsed);
		}
		return $parsed;
	}
	
	/**
	 * Set uniquely formatted data structure
	 * Usage example: $this->crud->set_data($data);
	 * @param array $data Data array
	 * @return NULL
	 */
	public function set_data($data) {
                // nullify all private variables
		$this->config = NULL;
		$this->data = NULL;
		$this->insert = NULL;
		$this->select = NULL;
		$this->update = NULL;
		$this->delete = NULL;
                $this->search = NULL;
		$this->datasource = NULL;
		$this->properties = NULL;
		$this->key_field = NULL;
		$this->fields = NULL;
		$this->pagination = NULL;
		$this->flashdata = NULL;
		
                // load oborci Crud config
		$this->CI->load->config('oborci_crud', TRUE);
		$this->config = $this->CI->config->item('oborci_crud');
		
                // validate options
		$data = $this->_get_crud_options($data);
		
                // breakdown data into smaller data segments
		$this->data = $data;
		$this->insert = $this->data['insert'];
		$this->select = $this->data['select'];
		$this->update = $this->data['update'];
		$this->delete = $this->data['delete'];
                $this->search = $this->data['search'];
		$this->datasource = $this->data['datasource'];
		$this->properties = $this->data['properties'];
		
		// insert fields
                if (is_array($this->insert)) {
                        foreach ($this->insert as $row) {
                                $fields['insert'][] = $row['name'];
                        }
                        $this->fields['insert'] = $fields['insert'];
                }
		
		// select fields
                if (is_array($this->select)) {
                        foreach ($this->select as $row) {
                                $row_name = $row['name'];
                                if (isset($row['table'])) {
                                        $row_name = $row['table'].'.'.$row_name;
                                }
                                $fields['select'][] = $row_name;
                                // set key field
                                if (isset($row['key'])) {
                                        $this->key_field = $row['name'];
                                }
                        }
                        $this->fields['select'] = $fields['select'];
                }
		
		// update fields
                if (is_array($this->update)) {
                        foreach ($this->update as $row) {
                                $fields['update'][] = $row['name'];
                        }
                        $this->fields['update'] = $fields['update'];
                }

		// delete fields
                if (is_array($this->delete)) {
                        foreach ($this->delete as $row) {
                                $fields['delete'][] = $row['name'];
                        }
                        $this->fields['delete'] = $fields['delete'];
                }
                
                // search fields
                if (is_array($this->search)) {
                        $this->fields['search'] = $this->search;
                }

		// table template options
		if (isset($this->properties['table_template'])) {
			$this->CI->table->set_template($this->properties['table_template']);
		} else {
			$this->CI->table->set_template($this->config['table_template']);
		}

		// pagination options
                $this->pagination['status'] = (boolean) $this->properties['pagination'];
		$this->pagination['per_page'] = (integer) (isset($this->properties['pagination_per_page']) ? $this->properties['pagination_per_page'] : '10');
	}

	/**
	 * Render CRUD form and grid
	 * Usage example: return $this->crud->render();
	 * @return string $returns Forms and grid
	 */
	public function render() {
		$returns = NULL;
		
		// get crud_action, insert, update, delete or handle actions
		// can be from previous form or fresh
		$crud_flashdata = $this->CI->session->userdata('crud_flashdata');
		if (isset($crud_flashdata['crud_action'])) {
			$crud_action = $crud_flashdata['crud_action'];
		} else {
			$crud_action = trim(strtolower($this->CI->input->post('crud_action')));
		}
		$this->flashdata = $crud_flashdata;
		$this->CI->session->unset_userdata('crud_flashdata');
		
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
				$key = $this->CI->input->post($this->key_field.'_0');
				if (isset($key)) {
					$returns .= $this->_form_update();
					return $returns;
				}
				break;
			case 'update_action':
				$returns .= $this->_form_update_action();
				return $returns;
				break;
			case 'delete':
				$key = $this->CI->input->post($this->key_field.'_0');
				if (isset($key)) {
					$returns .= $this->_form_delete();
					return $returns;
				}
				break;
			case 'delete_action':
				$returns .= $this->_form_delete_action();
				return $returns;
				break;
		}
		
		// grid
		$returns .= $this->_grid();
		return $returns;
	}

}

/* End of file Crud.php */
