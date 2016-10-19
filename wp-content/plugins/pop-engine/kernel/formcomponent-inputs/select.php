<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput MultipleOptions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_Select extends GD_FormInput {

	function get_default_value(/*$conf*/) {
	
		if ($this->is_multiple()) {
			return array();	
		}
		
		return parent::get_default_value(/*$conf*/);
	}

	function get_value(/*$conf, */$output = false) {
	
		$value = parent::get_value(/*$conf, */$output);
		if ($this->is_multiple()) {
			return array_filter($value);
		}

		return $value;		
	}

	function is_multiple() {

		return false;
	}

	/**
	 * Function to override
	 */	
	function get_all_values($label = null) {
	
		if ($label) {
			return array('' => $label);
		}

		return array();
	}
	
	
	function get_selected_value() {
	
		$all = $this->get_all_values();		
		if ($this->is_multiple()) {

			$ret = array();		
			if ($this->selected) {				
				foreach ($this->selected as $selected) {
				
					$ret[] = $all[$selected];
				}
			}
		}
		else {
			
			$ret = '';	
			if ($this->selected) {
				
				$ret = $all[$this->selected];
			}
		}
		
		return $ret;
	}
	
}