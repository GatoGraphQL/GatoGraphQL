<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CreateUserFormInnersBase extends GD_Template_Processor_FormInnersBase {

	function get_layouts($template_id) {

		$components =  array_merge(
			parent::get_layouts($template_id),
			array(
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERNAME,
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_PASSWORD,
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_PASSWORDREPEAT,
				GD_TEMPLATE_DIVIDER,
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_FIRSTNAME,
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_EMAIL,
				// GD_TEMPLATE_COLLAPSIBLEDIVIDER,
				// GD_TEMPLATE_FORMCOMPONENTGROUP_FILEUPLOAD_PICTURE,
				GD_TEMPLATE_COLLAPSIBLEDIVIDER,
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_DESCRIPTION,
				GD_TEMPLATE_COLLAPSIBLEDIVIDER,
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERURL,
				GD_TEMPLATE_DIVIDER,
				GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA,
				GD_TEMPLATE_SUBMITBUTTON_SUBMIT,
			)
		);

		// Hook for User Avatar
		$components = apply_filters('gd_template:createuser:components', $components, $template_id, $this);

		return $components;
	}

	function init_atts($template_id, &$atts) {

		// Make all formComponentGroups be collapsed if they are non-mandatory
		$layouts = $this->get_layouts($template_id);
		foreach ($layouts as $layout) {
	
			$this->add_att($layout, $atts, 'collapse-optionalfield', true);		
		}
		return parent::init_atts($template_id, $atts);
	}

	// function init_atts($template_id, &$atts) {

	// 	$this->add_extra_template_configuration(GD_TEMPLATE_FORMCOMPONENT_CUU_USERNAME, $atts, 'group-title', __('Log-in info', 'pop-coreprocessors'));

	// 	$this->add_extra_template_configuration(GD_TEMPLATE_FORMCOMPONENT_CUU_FIRSTNAME, $atts, 'hr', true);
	// 	$this->add_extra_template_configuration(GD_TEMPLATE_FORMCOMPONENT_CUU_FIRSTNAME, $atts, 'group-title', __('User info', 'pop-coreprocessors'));

	// 	$this->add_extra_template_configuration(GD_TEMPLATE_FORMCOMPONENT_CAPTCHA, $atts, 'hr', true);

	// 	// Add common Create/Update atts
	// 	GD_Template_Processor_CreateUpdateUserFormsUtils::init_atts($template_id, $atts, $this);
		
	// 	return parent::init_atts($template_id, $atts);
	// }
}