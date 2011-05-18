<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form library
 *
 * @author Anton Raharja
 */
class Form {

        public $uri = NULL;
        public $name = NULL;
        
        private $data = NULL;
        private $rules = NULL;
	private $form_name = 'Form';
	private $CI = NULL;

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
                $uri = isset($this->uri) ? $this->uri : $data['uri'];
                if (empty($uri)) {
                        $uri = current_url();
                }
                $this->data['uri'] = $uri;
                $name = isset($this->name) ? $this->name : $data['name'];
                if (empty($name)) {
                        $name = 'form'.mktime();
                }
                $data['name'] = $name;
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		$returns = form_open($uri, $data);
                $this->data[] = array('open' => $data);
		return $returns;
	}

	/**
	 * Create form close
	 * @param array $data Data array
	 * @return string $returns Form close
	 */
	public function close($data=NULL) {
		$returns = form_close($data['value']);
                $this->data[] = array('close' => $data);
		return $returns;
	}

	/**
	 * Create checkbox
	 * @param array $data Data array
	 * @return string $returns Checkbox
	 */
	public function checkbox($data=NULL) {
		$returns = NULL;
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['checked']) {
			$data['checked'] =  'checked';
		}
		$returns .= form_checkbox($data);
                $this->data[] = array('checkbox' => $data);
		return $returns;
	}

	/**
	 * Create radio button
	 * @param array $data Data array
	 * @return string $returns Radio button
	 */
	public function radio($data=NULL) {
		$returns = NULL;
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['checked']) {
			$data['checked'] =  'checked';
		}
		$returns .= form_radio($data);
                $this->data[] = array('radio' => $data);
		return $returns;
	}

	/**
	 * Create dropdown
	 * @param array $data Data array
	 * @return string $returns Dropdown
	 */
	public function dropdown($data=NULL) {
		$extra = NULL;
		$returns = NULL;
		$returns .= '<div id="form_dropdown">';
		$name = $data['name'];
		$options = $data['options'];
		$selected = isset($data['selected']) ? $data['selected'] : '';
		$data['extra']['id'] = isset($data['id']) ? $data['id'] : $data['extra']['id'];
		$data['extra']['id'] = isset($data['extra']['id']) ? $data['extra']['id'] : $data['name'];
		foreach ($data['extra'] as $key => $val) {
			$extra .= $key.'='.$val.' ';			
		}		
		if ($data['label']) {
			$attr = array('id' => $data['extra']['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
		$returns .= form_dropdown($name, $options, $selected, $extra);
		$returns .= "</div>";
                $this->data[] = array('dropdown' => $data);
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
                $this->data[] = array('hidden' => $data);
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
                $this->data[] = array('label' => $data);
		return $returns;
	}

	/**
	 * Create text input
	 * @param array $data Data array
	 * @return string $returns Text input
	 */
	public function input($data=NULL) {
		$returns = NULL;
		$returns .= '<div id="form_input">';
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
		$returns .= form_input($data);
		$returns .= "</div>";
                $this->data[] = array('input' => $data);
		return $returns;
	}

	/**
	 * Create password input
	 * @param array $data Data array
	 * @return string $returns Password input
	 */
	public function password($data=NULL) {
		$returns = NULL;
		$returns .= '<div id="form_password">';
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
		$returns .= form_password($data);
		$returns .= "</div>";
                $this->data[] = array('password' => $data);
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
                $this->data[] = array('submit' => $data);
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
                $this->data[] = array('reset' => $data);
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
                $this->data[] = array('button' => $data);
		return $returns;
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
         * Initialize form, nullify all parameters and start fresh
         */
        public function initilize() {
                $this->_nullify_params();
        }

	/**
         * Set rules to each field
         * @param array $data Rules array
         */
        public function set_rules($data) {
                $this->rules = $data;
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
         * Call function on event on_success
         * @param array $data Function
         */
        public function on_success($data) {
                $this->data['events']['on_success'] = $data;
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
                
                $data = $this->data;
                $rules = $this->rules;
                $this->_nullify_params();
                
		foreach ($data as $field_key => $field_val) {
                        // echo $field_key.' -- '.print_r($field_val, TRUE).'<br />';
                        foreach ($field_val as $key => $val) {
                                $data[$field_key][$key]['rules'] = $rules[$val['name']];
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
                
                // echo print_r($data, TRUE).'<br /><br />'.print_r($rules, TRUE);
                
                $this->_nullify_params();
		return $returns;
	}

        /**
         * Helper function to nullify parameters
         */
        private function _nullify_params() {
                $this->data = NULL;
                $this->rules = NULL;
                $this->uri = NULL;
                $this->name = NULL;
        }
}

/* End of file Form.php */
