<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form library
 *
 * @author Anton Raharja
 */
class Form {

	private $data = NULL;
	private $hidden = NULL;
	private $submit = NULL;
	private $CI = NULL;
	private $form_name = 'Form';

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->helper('form');
	}

	/**
	 * Create form open
	 * @param array $data Data array
	 * @return string $returns Form open
	 */
	public function open($data=NULL) {
		$id = isset($data['id']) ? $data['id'] : $data['name'];
		$returns = form_open($data['uri'], array('name' => $data['name'], 'id' => $id));
		return $returns;
	}

	/**
	 * Create form close
	 * @param array $data Data array
	 * @return string $returns Form close
	 */
	public function close($data=NULL) {
		$returns = form_close($data['value']);
		return $returns;
	}

	/**
	 * Create checkbox
	 * @param array $data Data array
	 * @return string $returns Checkbox
	 */
	public function checkbox($data=NULL) {
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['checked']) {
			$data['checked'] =  'checked';
		}
		$returns = form_checkbox($data);
		return $returns;
	}

	/**
	 * Create radio button
	 * @param array $data Data array
	 * @return string $returns Radio button
	 */
	public function radio($data=NULL) {
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['checked']) {
			$data['checked'] =  'checked';
		}
		$returns = form_radio($data);
		return $returns;
	}

	/**
	 * Create dropdown
	 * @param array $data Data array
	 * @return string $returns Dropdown
	 */
	public function dropdown($data=NULL) {
		$extra = NULL;
		$name = $data['name'];
		$options = $data['options'];
		$selected = isset($data['selected']) ? $data['selected'] : '';
		$data['extra']['id'] = isset($data['id']) ? $data['id'] : $data['extra']['id'];
		$data['extra']['id'] = isset($data['extra']['id']) ? $data['extra']['id'] : $data['name'];
		foreach ($data['extra'] as $key => $val) {
			$extra .= $key.'='.$val.' ';			
		}		
		$returns = form_dropdown($name, $options, $selected, $extra);
		return $returns;
	}

	/**
	 * Create hidden input
	 * @param array $data Data array
	 * @return string $returns Hidden input
	 */
	public function hidden($data=NULL) {
		$name = $data['name'];
		$value = $data['value'];
		$returns = form_hidden($name, $value);
		return $returns;
	}

	/**
	 * Create label
	 * @param array $data Data array
	 * @return string $returns Label
	 */
	public function label($data=NULL) {
		$returns = NULL;
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
		return $returns;
	}

	/**
	 * Create text input
	 * @param array $data Data array
	 * @return string $returns Text input
	 */
	public function input($data=NULL) {
		$returns = NULL;
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
		if ($data['readonly']) {
			$data['readonly'] = 'readonly';
		}
		if ($data['disabled']) {
			$data['disabled'] = 'disabled';
		}
		unset($data['label']);
		unset($data['unique']);
		unset($data['confirm']);
		unset($data['confirm_label']);
		unset($data['show_value']);
		$returns .= form_input($data);
		return $returns;
	}

	/**
	 * Create password input
	 * @param array $data Data array
	 * @return string $returns Password input
	 */
	public function password($data=NULL) {
		$returns = NULL;
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
		unset($data['label']);
		unset($data['unique']);
		unset($data['confirm']);
		unset($data['confirm_label']);
		unset($data['show_value']);
		$returns .= form_password($data);
		return $returns;
	}
	
	/**
	 * Create submit button
	 * @param array $data Data array
	 * @return string $returns Submit button
	 */
	public function submit($data=NULL) {
		if (! isset($data['name'])) {
			$data['name'] = 'form_submit';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		$returns = form_submit($data);
		return $returns;
	}

	/**
	 * Create reset button
	 * @param array $data Data array
	 * @return string $returns Reset button
	 */
	public function reset($data=NULL) {
		if (! isset($data['name'])) {
			$data['name'] = 'form_reset';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		$returns = form_reset($data);
		return $returns;
	}

	/**
	 * Create plain button
	 * @param array $data Data array
	 * @return string $returns Plain button
	 */
	public function button($data=NULL) {
		if (! isset($data['name'])) {
			$data['name'] = 'form_button';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		$returns = form_button($data);
		return $returns;
	}
	
	/**
	 * Set uniquely formatted data structure
	 * Usage example: $this->form->set_data($data);
	 * @param array $data Data array
	 * @return NULL
	 */
	public function set_data($data) {
		$this->data = $data;
	}

	/**
	 * Print form element
	 * Usage example: $this->form->show('submit', array('value' => 'Submit');
	 * @param string $element Form element
	 * @param array $data Data array
	 * @return NULL
	 */
	public function show($element, $data) {
		$data = NULL;
		if (method_exists($this->form_name, $element)) {
			$data .= call_user_func_array(array($this->form_name, $element), array($data));
		}
		echo $data;
	}

	/**
	 * Render form
	 * Usage example: return $this->form->render();
	 * @return string $returns Form
	 */
	public function render() {
		$returns = NULL;
		$form_open_exists = FALSE;
		$form_close_exists = FALSE;
		foreach ($this->data as $row) {
			foreach ($row as $key => $val) {
				if (method_exists($this->form_name, $key)) {
					$returns .= call_user_func_array(array($this->form_name, $key), array($val));
				}
				if ($key == 'open') {
					$form_open_exists = TRUE;
				}
				if ($key == 'close') {
					$form_close_exists = TRUE;
				}
			}
		}
		if ($form_open_exists && !$form_close_exists) {
			$returns .= $this->close();
		}
		return $returns;
	}

}

/* End of file Form.php */
