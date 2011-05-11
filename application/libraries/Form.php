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
	 * @return string $data Form open
	 */
	public function open($data=NULL) {
		$id = isset($data['id']) ? $data['id'] : $data['name'];
		return form_open($data['uri'], array('name' => $data['name'], 'id' => $id));
	}

	/**
	 * Create form close
	 * @param array $data Data array
	 * @return string $data Form close
	 */
	public function close($data=NULL) {
		return form_close($data['value']);
	}

	/**
	 * Create checkbox
	 * @param array $data Data array
	 * @return string $data Checkbox
	 */
	public function checkbox($data=NULL) {
		$returns = "<div id='form_checkbox'>";
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		$returns .= form_checkbox($data);
		$returns .= "</div>";
		return $returns;
	}

	/**
	 * Create radio button
	 * @param array $data Data array
	 * @return string $data Radio button
	 */
	public function radio($data=NULL) {
		$returns = "<div id='form_radio'>";
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		$returns .= form_radio($data);
		$returns .= "</div>";
		return $returns;
	}

	/**
	 * Create dropdown
	 * @param array $data Data array
	 * @return string $data Dropdown
	 */
	public function dropdown($data=NULL) {
		$returns = "<div id='form_dropdown'>";
		$name = $data['name'];
		$options = $data['options'];
		$selected = isset($data['selected']) ? $data['selected'] : '';
		$data['extra']['id'] = isset($data['extra']['id']) ? $data['extra']['id'] : $data['name'];
		foreach ($data['extra'] as $key => $val) {
			$extra .= $key.'='.$val.' ';			
		}		
		$returns .= form_dropdown($name, $options, $selected, $extra);
		$returns .= "</div>";
		return $returns;
	}

	/**
	 * Create hidden input
	 * @param array $data Data array
	 * @return string $data Hidden input
	 */
	public function hidden($data=NULL) {
		$name = $data['name'];
		$value = $data['value'];
		return form_hidden($name, $value);
	}

	/**
	 * Create text input
	 * @param array $data Data array
	 * @return string $data Text input
	 */
	public function input($data=NULL) {
		$returns = "<div id='form_input'>";
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
		$returns .= form_input($data);
		$returns .= "</div>";
		return $returns;
	}

	/**
	 * Create password input
	 * @param array $data Data array
	 * @return string $data Password input
	 */
	public function password($data=NULL) {
		$returns = "<div id='form_password'>";
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
		$returns .= form_password($data);
		$returns .= "</div>";
		return $returns;
	}
	
	/**
	 * Create submit button
	 * @param array $data Data array
	 * @return string $data Submit button
	 */
	public function submit($data=NULL) {
		if (! isset($data['name'])) {
			$data['name'] = 'form_submit';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		return form_submit($data);
	}

	/**
	 * Create reset button
	 * @param array $data Data array
	 * @return string $data Reset button
	 */
	public function reset($data=NULL) {
		if (! isset($data['name'])) {
			$data['name'] = 'form_reset';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		return form_reset($data);
	}

	/**
	 * Create plain button
	 * @param array $data Data array
	 * @return string $data Plain button
	 */
	public function button($data=NULL) {
		if (! isset($data['name'])) {
			$data['name'] = 'form_button';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		return form_button($data);
	}
	
	/**
	 * Set uniquely formatted data structure
	 * Usage example: $this->form->set_data($data);
	 * @param array $data Data array
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
	 * @return string $data Form
	 */
	public function render() {
		$data = NULL;
		$form_open_exists = FALSE;
		$form_close_exists = FALSE;
		foreach ($this->data as $key => $val) {
			if (method_exists($this->form_name, $key)) {
				$data .= call_user_func_array(array($this->form_name, $key), array($val));
			}
			if ($key == 'open') {
				$form_open_exists = TRUE;
			}
			if ($key == 'close') {
				$form_close_exists = TRUE;
			}
		}
		if ($form_open_exists && !$form_close_exists) {
			$data .= $this->close();
		}
		return $data;
	}

}

/* End of file Form.php */
