<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_LAYOUTWRAPPER_ORGANIZATIONMEMBERS', PoP_TemplateIDUtils::get_template_definition('ure-layoutwrapper-organizationmembers'));

class GD_URE_Template_Processor_MembersLayoutWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_LAYOUTWRAPPER_ORGANIZATIONMEMBERS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_LAYOUTWRAPPER_ORGANIZATIONMEMBERS:

				$ret[] = GD_URE_TEMPLATE_CODE_MEMBERSLABEL;
				$ret[] = GD_URE_TEMPLATE_MULTICOMPONENT_ORGANIZATIONMEMBERS;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_URE_TEMPLATE_LAYOUTWRAPPER_ORGANIZATIONMEMBERS:

				return 'has-members';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_MembersLayoutWrappers();