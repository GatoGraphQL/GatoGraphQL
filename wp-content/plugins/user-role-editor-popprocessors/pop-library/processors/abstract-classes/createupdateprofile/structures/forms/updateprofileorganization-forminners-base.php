<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_Template_Processor_UpdateProfileOrganizationFormInnersBase extends GD_Template_Processor_UpdateProfileFormInnersBase {

	function get_layouts($template_id) {
	
		$ret = parent::get_layouts($template_id);
		
		// Add common Create/Update components
		// GD_Template_Processor_CreateUpdateProfileOrganizationFormsUtils::get_components($template_id, $ret, $this);
		// Add extra components
		$extra_components_communities = array(
			GD_TEMPLATE_DIVIDER,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_ISCOMMUNITY,
		);
		array_splice($ret, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_DESCRIPTION, $ret)+1, 0, $extra_components_communities);

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Change the label
		$this->add_att(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_FIRSTNAME, $atts, 'label', __('Organization Name*', 'ure-popprocessors'));
		$this->add_att(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_EMAIL, $atts, 'label', __('Organization Email*', 'ure-popprocessors'));

		$this->add_att(GD_URE_TEMPLATE_FORMCOMPONENT_CUP_ISCOMMUNITY, $atts, 'load-itemobject-value', true);
		
		return parent::init_atts($template_id, $atts);
	}
}