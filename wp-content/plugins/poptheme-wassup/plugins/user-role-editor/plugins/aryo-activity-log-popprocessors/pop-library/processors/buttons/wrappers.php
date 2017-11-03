<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_EDITMEMBERSHIP', PoP_TemplateIDUtils::get_template_definition('ure-aal-buttonwrapper-editmembership'));
define ('GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_VIEWALLMEMBERS', PoP_TemplateIDUtils::get_template_definition('ure-aal-buttonwrapper-viewallmembers'));

class Custom_URE_AAL_PoPProcessors_Template_Processor_ButtonWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_EDITMEMBERSHIP,
			GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_VIEWALLMEMBERS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_EDITMEMBERSHIP:

				$ret[] = GD_URE_AAL_TEMPLATE_BUTTON_EDITMEMBERSHIP;
				break;

			case GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_VIEWALLMEMBERS:

				$ret[] = GD_URE_AAL_TEMPLATE_BUTTON_VIEWALLMEMBERS;
				break;
		}

		return $ret;
	}

	

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);
	
		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_EDITMEMBERSHIP:
			case GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_VIEWALLMEMBERS:

				$ret[] = GD_TEMPLATE_HIDEIFEMPTY;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_EDITMEMBERSHIP:
			case GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_VIEWALLMEMBERS:

				return 'object-id';
		}

		return parent::get_condition_field($template_id);
	}

	function get_condition_method($template_id) {

		switch ($template_id) {
					
			case GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_EDITMEMBERSHIP:
			case GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_VIEWALLMEMBERS:

				return 'isUserIdSameAsLoggedInUser';
		}

		return parent::get_condition_method($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Custom_URE_AAL_PoPProcessors_Template_Processor_ButtonWrappers();