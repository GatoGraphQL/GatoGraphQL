<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_VarsHashProcessor {

	function __construct() {

		add_action('init', array($this, 'init'));
	}

	function init() {

		global $gd_template_varshashprocessor_manager;
		$gd_template_varshashprocessor_manager->add($this, $this->get_templates_to_process());
	}

	function get_templates_to_process() {
	
		return array();
	}

	function get_vars_hash_id($template_id) {

		// Return the filename coded, otherwise it may become too long and produces errors accessing the file
		if ($filename = $this->get_vars_hash_id_by_toplevel($template_id)) {

			$filename .= PoP_VarsUtils::get_vars_identifier();

			// Do not start with a number (just in case)
			return 'c'.md5($filename);
		}

		return false;
	}

	protected function get_vars_hash_id_by_toplevel($template_id) {

		return '';
	}
}