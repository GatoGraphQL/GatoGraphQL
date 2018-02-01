<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_VarsHashProcessor_Utils {

	public static function get_vars_hash_id() {
	
		global $gd_template_varshashprocessor_manager;
        $engine = PoP_Engine_Factory::get_instance();
		$toplevel_template_id = $engine->get_toplevel_template_id();

		// First check if the resources have been cached from when executing /generate-theme/
		$processor = $gd_template_varshashprocessor_manager->get_processor($toplevel_template_id);
		return $processor->get_vars_hash_id($toplevel_template_id);
	}
}