<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_Template_Processor_CreateProfileIndividualFormInnersBase extends GD_Template_Processor_CreateProfileFormInnersBase {

	function get_layouts($template_id) {
	
		$ret = parent::get_layouts($template_id);

		// Add common Create/Update components
		GD_Template_Processor_CreatProfileFormsUtils::get_components($template_id, $ret, $this);
		GD_Template_Processor_CreateUpdateProfileIndividualFormsUtils::get_components($template_id, $ret, $this);

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Change the title for the Individual Description
		$this->add_att(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_DESCRIPTION, $atts, 'label', __('Tell us about yourself', 'ure-popprocessors'));		
		return parent::init_atts($template_id, $atts);
	}
}