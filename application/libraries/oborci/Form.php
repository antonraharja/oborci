<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form generation library for CodeIgniter
 *
 * @author Anton Raharja
 * @version 0.9
 * @see http://github.com/antonraharja/oborci
 */
class Form {
        
        private $data = NULL;
        private $returns = NULL;
        private $message = NULL;
        private $uri = NULL;
        private $name = NULL;
        private $rules = NULL;
        private $on_success = NULL;
        private $render = FALSE;
	private $CI = NULL;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->helper('form');
	}

	/**
	 * Create form open
	 * @param array $data Data array
         * @return string HTML form open
	 */
	public function open($data=NULL) {
                $form_action = NULL;
                $uri = NULL;
                if (isset($this->uri)) {
                        $uri = $this->uri;
                } else {
                        $uri = $data['uri'];
                        if (empty($uri)) {
                                $uri = current_url();
                        }
                }
                if (isset($this->on_success)) {
                        $data['form_action'] = array('form_action' => 'auto');
                }
                $name = isset($this->name) ? $this->name : $data['name'];
                $data['name'] = $name;
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
                if (! $this->render) { 
                        $data['id'] = 'form_'.$data['id'];
                        $data['id'] = str_replace('form_form_', 'form_', $data['id']);
                        $data['uri'] = $uri;
                        $this->data[] = array('open' => $data);
                }
                $data = $this->_sanitize_param($data);
		$returns = form_open($uri, $data, $data['form_action']);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create form close
	 * @param array $data Data array
         * @return string HTML form close
	 */
	public function close($data=NULL) {
                if (! $this->render) { 
                        $this->data[] = array('close' => $data);
                }
                $data = $this->_sanitize_param($data);
		$returns = form_close($data['value']);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create checkbox
	 * @param array $data Data array
         * @return string HTML form checkbox
	 */
	public function checkbox($data=NULL) {
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['checked']) {
			$data['checked'] =  'checked';
		}
                if (! $this->render) { 
                        $data['id'] = 'form_'.$data['id'];
                        $data['id'] = str_replace('form_form_', 'form_', $data['id']);
                        $this->data[] = array('checkbox' => $data);
                }
                $data = $this->_sanitize_param($data);
		$returns = form_checkbox($data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create radio button
	 * @param array $data Data array
         * @return string HTML form radio
	 */
	public function radio($data=NULL) {
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['checked']) {
			$data['checked'] =  'checked';
		}
                if (! $this->render) { 
                        $data['id'] = 'form_'.$data['id'];
                        $data['id'] = str_replace('form_form_', 'form_', $data['id']);
                        $this->data[] = array('radio' => $data);
                }
                $data = $this->_sanitize_param($data);
		$returns = form_radio($data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create dropdown
	 * @param array $data Data array
         * @return string HTML form dropdown
	 */
	public function dropdown($data=NULL) {
		$extra = NULL;
		$returns = '<div id="form_dropdown">';
		$name = $data['name'];
		$options = $data['options'];
		$selected = isset($data['selected']) ? $data['selected'] : '';
		$data['extra']['id'] = isset($data['id']) ? $data['id'] : $data['extra']['id'];
		$data['extra']['id'] = isset($data['extra']['id']) ? $data['extra']['id'] : $data['name'];
		foreach ($data['extra'] as $key => $val) {
			$extra .= $key.'='.$val.' ';			
		}		
                if (! $this->render) { 
                        $data['extra']['id'] = 'form_'.$data['extra']['id'];
                        $data['extra']['id'] = str_replace('form_form_', 'form_', $data['extra']['id']);
                        $this->data[] = array('dropdown' => $data);
                }
		if ($data['label']) {
			$attr = array('id' => $data['extra']['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
		$returns .= form_dropdown($name, $options, $selected, $extra);
		$returns .= "</div>";
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create hidden input
	 * @param array $data Data array
         * @return HTML form hidden
	 */
	public function hidden($data=NULL) {
		$name = $data['name'];
		$value = $data['value'];
                if (! $this->render) { 
                        $this->data[] = array('hidden' => $data);
                }
                $data = $this->_sanitize_param($data);
		$returns = form_hidden($name, $value);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create label
	 * @param array $data Data array
         * @return string HTML form label
	 */
	public function label($data=NULL) {
		$returns = NULL;
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
                if (! $this->render) { 
                        $this->data[] = array('label' => $data);
                }
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create text input
	 * @param array $data Data array
         * @param array $messages Array of error messages
         * @return string HTML form input
	 */
	public function input($data=NULL, $messages=NULL) {
		$returns = '<div id="form_input">';
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['readonly']) {
			$data['readonly'] = 'readonly';
		}
		if ($data['disabled']) {
			$data['disabled'] = 'disabled';
		}
                if (! $this->render) { 
                        $data['id'] = 'form_'.$data['id'];
                        $data['id'] = str_replace('form_form_', 'form_', $data['id']);
                        $this->data[] = array('input' => $data);
                }
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
                $data = $this->_sanitize_param($data);
		$returns .= form_input($data);
                if (is_array($messages)) {
                        foreach ($messages as $message) {
                                $returns .= '<div id="form_invalid_message">'.$message.'</div>';
                        }
                }
		$returns .= "</div>";
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create password input
	 * @param array $data Data array
         * @param array $messages Array of error messages
         * @return string HTML form password
	 */
	public function password($data=NULL, $messages=NULL) {
		$returns = '<div id="form_password">';
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
                if (! $this->render) { 
                        $data['id'] = 'form_'.$data['id'];
                        $data['id'] = str_replace('form_form_', 'form_', $data['id']);
                        $this->data[] = array('password' => $data);
                }
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
                $data = $this->_sanitize_param($data);
		$returns .= form_password($data);
                if (is_array($messages)) {
                        foreach ($messages as $message) {
                                $returns .= '<div id="form_invalid_message">'.$message.'</div>';
                        }
                }
		$returns .= "</div>";
                $this->returns[] = $returns;
                return $returns;
	}
	
	/**
	 * Create submit button
	 * @param array $data Data array
         * @return string HTML form submit
	 */
	public function submit($data=NULL) {
		if (! isset($data['name'])) {
			$data['name'] = 'submit';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
                if (! $this->render) { 
                        $data['id'] = 'form_'.$data['id'];
                        $data['id'] = str_replace('form_form_', 'form_', $data['id']);
                        $this->data[] = array('submit' => $data);
                }
                $data = $this->_sanitize_param($data);
		$returns = form_submit($data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create reset button
	 * @param array $data Data array
         * @return string HTML form reset
	 */
	public function reset($data=NULL) {
		if (! isset($data['name'])) {
			$data['name'] = 'reset';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
                if (! $this->render) { 
                        $data['id'] = 'form_'.$data['id'];
                        $data['id'] = str_replace('form_form_', 'form_', $data['id']);
                        $this->data[] = array('reset' => $data);
                }
                $data = $this->_sanitize_param($data);
		$returns = form_reset($data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create plain button
	 * @param array $data Data array
         * @return string HTML form button
	 */
	public function button($data=NULL) {
		if (! isset($data['name'])) {
			$data['name'] = 'button';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
                if (! $this->render) { 
                        $data['id'] = 'form_'.$data['id'];
                        $data['id'] = str_replace('form_form_', 'form_', $data['id']);
                        $this->data[] = array('button' => $data);
                }
                $data = $this->_sanitize_param($data);
		$returns = form_button($data);
                $this->returns[] = $returns;
                return $returns;
	}
        
        /**
         * Initialize form, nullify all parameters and start fresh
         */
        public function init() {
                $this->_nullify_variables();
        }

	/**
         * Set rules to each field
         * @param array $data Rules array
         */
        public function set_rules($data) {
                $this->rules = $data;
        }
        
	/**
         * Set name of the form
         * @param string $data Form name
         */
        public function set_name($data) {
                $this->name = $data;
        }
        
	/**
         * Set action URI of the form.
         * Form will set URI to current_url() and activate auto validation and callback on event on_success
         * when URI is not provided through this method or directly injected on open().
         * @param string $data Form action URI
         */
        public function set_uri($data) {
                $this->uri = $data;
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
         * Callback a method or a function when validation process is done and succeeded.
         * This event generated when action URI is not set by set_uri() or directly injected to open()
         * @param array $data Method or function, example: array('$this', 'foo') for method $this->foo
         */
        public function set_on_success($data) {
                $this->on_success = $data;
        }

	/**
	 * Render form
	 * Usage example: return $this->form->render();
	 * @return string $returns HTML form
	 */
	public function render() {
                // set render flag to true so that methods are not re-inserting data to $this-data
                $this->render = TRUE;
                
                // compile data, one of the is to setup rules
                $this->data = $this->_compile_data();
                
                // get form_action, form_action sets when uri is current_url()
                $form_action = $this->CI->input->post('form_action');
                
                if ($form_action=='auto') {
                        if (isset($this->on_success)) {
                                list($valid, $inputs, $messages) = $this->_validate_inputs();
                                if ($valid) {
                                        $returns = call_user_func_array($this->on_success, array($inputs));
                                        if ($returns === FALSE) {
                                                $message = t('Fail to process form input');
                                        } else {
                                                if (isset($returns)) {
                                                        $message = $returns;
                                                } else {
                                                        $message = t('Form input has been processed');
                                                }
                                        }
                                        $this->message = '<div id="form_action_message">'.$message.'</div>';
                                } else {
                                        // array of in-validated inputs message
                                        $this->message = $messages;
                                }
                        }
                }
                
                // generate HTML form
                $returns = $this->_generate_form();

                // nullify all private variables
                $this->_nullify_variables();
                
                // set render flag back to false
                $this->render = FALSE;
                
                return $returns;
	}
        
        /**
         * Validate user inputs from form
         * @return array An array contains validation status
         */
        private function _validate_inputs() {
                $valid = TRUE;
                $inputs = NULL;
                $messages = NULL;
                $array_ignored = array('open', 'close', 'submit', 'reset', 'button');
                $data = $this->data;
                foreach ($data as $field_key => $field_val) {
                        foreach ($field_val as $method => $param) {
                                if (! (in_array($method, $array_ignored))) {
                                        $name = $data[$field_key][$method]['name'];
                                        $inputs[$name] = $this->CI->input->post($name);
                                        foreach ($param as $param_key => $param_val) {
                                                if ($param_key=='apply_function') {
                                                        foreach ($param_val as $i => $function) {
                                                                if (function_exists($function)) {
                                                                        $inputs[$name] = call_user_func($function, $inputs[$name]);
                                                                }
                                                        }
                                                }
                                                if ($param_key=='required') {
                                                        if (! isset($inputs[$name])) {
                                                                $messages[$name][] = t('You must fill this field');
                                                                $valid = FALSE;
                                                        }
                                                }
                                                if (($param_key=='readonly') || ($param_key=='disabled')) {
                                                        unset($inputs[$name]);
                                                }
                                                if (($param_key=='max_length') && (strlen($inputs[$name]) > $param_val)) {
                                                        $param_val = $param_val>0 ? $param_val : '0';
                                                        $messages[$name][] = t('Maximum length of input is').' '.$param_val.' '.t('character');
                                                        $valid = FALSE;
                                                }
                                                if (($param_key=='min_length') && (strlen($inputs[$name]) < $param_val)) {
                                                        $param_val = $param_val>0 ? $param_val : '0';
                                                        $messages[$name][] = t('Minimum length of input is').' '.$param_val.' '.t('character');
                                                        $valid = FALSE;
                                                }
                                        }
                                }
                        }
                }
                $returns = array($valid, $inputs, $messages);
                return $returns;
        }
        
        /**
         * Helper funtion to process form generation
         * @return string HTML form
         */
        private function _generate_form() {
                $returns = NULL;
                if (isset($this->message) && (! is_array($this->message))) {
                        $returns = $this->message;
                } else {
                        $form_open_exists = FALSE;
                        $form_close_exists = FALSE;
                        $data = $this->data;
                        $message = $this->message;
                        $this->_nullify_variables();
                        foreach ($data as $field_key => $field_val) {
                                foreach ($field_val as $method => $param) {
                                        if (method_exists(__CLASS__, $method)) {
                                                if (($method=='input') || ($method=='password')) {
                                                        $messages = $message[$param['name']];
                                                        call_user_func_array(array(__CLASS__, $method), array($param, $messages));
                                                } else {
                                                        call_user_func_array(array(__CLASS__, $method), array($param));
                                                }
                                        }
                                        if ($method == 'open') {
                                                $form_open_exists = TRUE;
                                        }
                                        if ($method == 'close') {
                                                $form_close_exists = TRUE;
                                        }
                                }
                        }
                        if ($form_open_exists && !$form_close_exists) {
                                $this->close();
                        }
                        $returns = implode($this->returns);
                        $this->_nullify_variables();
                }
                return $returns;
        }

        /**
         * Sanitize parameters, removed unknown HTML field options
         * @param array Parameters
         * @return array Sanitized parameters before HTML generation
         */
        private function _sanitize_param($param) {
                unset($param['apply_function']);
                unset($param['max_length']);
                unset($param['min_length']);
                unset($param['label']);
                unset($param['uri']);
                return $param;
        }
        
        /**
         * Helper function to nullify parameters
         */
        private function _nullify_variables() {
                $this->data = NULL;
                $this->returns = NULL;
                $this->message = NULL;
                $this->rules = NULL;
                $this->uri = NULL;
                $this->name = NULL;
                $this->on_success = NULL;
        }
        
        /**
         * Helper function to setup rules in data array
         * @return array Compiled data
         */
        private function _compile_data() {
                $rules_boolean = array('required', 'readonly', 'disabled');
                $rules_special = array('max_length', 'min_length');
                $data = $this->data;
                $rules = $this->rules;
                
                // setup rules
                if (isset($this->rules)) {
                        foreach ($data as $field_key => $field_val) {
                                foreach ($field_val as $method => $param) {
                                        $name = $data[$field_key][$method]['name'];
                                        if (is_array($rules[$name])) {
                                                foreach ($rules[$name] as $rule_key => $rule_val) {
                                                        if (is_array($rule_val)) {
                                                                foreach ($rule_val as $sub_rule_key => $sub_rule_val) {
                                                                        if (in_array($sub_rule_key, $rules_special)) {
                                                                                $data[$field_key][$method][$sub_rule_key] = $sub_rule_val;
                                                                        }
                                                                }
                                                        } else {
                                                                if (in_array($rule_val, $rules_boolean)) {
                                                                        $data[$field_key][$method][$rule_val] = TRUE;
                                                                } else {
                                                                        if (function_exists($rule_val)) {
                                                                                $data[$field_key][$method]['apply_function'][] = $rule_val;
                                                                        }
                                                                }
                                                        }
                                                }
                                        }
                                }
                        }
                        unset($data[$field_key][$method]['rules']);
                }
                
                return $data;
        }
}

/* End of file Form.php */
