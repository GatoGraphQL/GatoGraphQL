<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CreateProfileFormInnersBase extends GD_Template_Processor_CreateUserFormInnersBase {

	function get_layouts($template_id) {

		$components = parent::get_layouts($template_id, $atts);
	
		// Add common Create/Update components
		GD_Template_Processor_CreateUpdateProfileFormsUtils::get_components($template_id, $components, $this);

		// Hook for Newsletter
		$components = apply_filters('gd_template:createprofile:components', $components, $template_id, $this);
		
		return $components;
	}
}