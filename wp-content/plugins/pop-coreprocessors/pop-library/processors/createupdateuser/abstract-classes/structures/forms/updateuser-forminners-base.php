<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UpdateUserFormInnersBase extends GD_Template_Processor_FormInnersBase {

	function get_layouts($template_id) {

		return array_merge(
			parent::get_layouts($template_id),
			array(
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERNAME,
				GD_TEMPLATE_DIVIDER,
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_FIRSTNAME,
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_EMAIL,
				GD_TEMPLATE_DIVIDER,
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_DESCRIPTION,
				GD_TEMPLATE_DIVIDER,
				GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERURL,
				GD_TEMPLATE_SUBMITBUTTON_UPDATE,
			)
		);
	}

	function init_atts($template_id, &$atts) {

		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUU_USERNAME, $atts, 'readonly', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUU_USERNAME, $atts, 'load-itemobject-value', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUU_EMAIL, $atts, 'load-itemobject-value', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUU_FIRSTNAME, $atts, 'load-itemobject-value', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUU_DESCRIPTION, $atts, 'load-itemobject-value', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUU_USERURL, $atts, 'load-itemobject-value', true);

		// // Add common Create/Update atts
		// GD_Template_Processor_CreateUpdateUserFormsUtils::init_atts($template_id, $atts, $this);
		
		return parent::init_atts($template_id, $atts);
	}
}