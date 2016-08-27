<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput ArticleCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_MultipleInputs extends GD_FormInput_MultiSelect {

	var $subnames;
	
	function get_subnames() {
	
		return $this->subnames;
	}
	
	function __construct($params = array()) {
	
		parent::__construct($params);
		$this->subnames = $params['subnames'] ? $params['subnames'] : array();

		// Re-implement to get the values to get from all the subnames
		if (isset($params['selected'])) {

			$selected = $params['selected'];
		}
		else {

			$selected = array();
			foreach ($this->get_subnames() as $subname) {
			
				$value = $_REQUEST[$this->get_name() . '-' . $subname];
				if ($value) {
					$selected[$subname] = $value;
				}
			}
		}
		$this->selected = $selected;
	}
	
	// function get_value() {
	
	// 	$selected = array();
	// 	foreach ($this->get_subnames() as $subname) {
		
	// 		$value = $_REQUEST[$this->get_name() . '-' . $subname];
	// 		if ($value) {
	// 			$selected[$subname] = $value;
	// 		}
	// 	}
	
	// 	return $selected;
	// }		
}
