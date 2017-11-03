<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS', PoP_TemplateIDUtils::get_template_definition('ure-layoutwrapper-profileindividual-details'));
define ('GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS', PoP_TemplateIDUtils::get_template_definition('ure-layoutwrapper-profileorganization-details'));

class GD_URE_Custom_Template_Processor_SidebarComponentsWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS,
			GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS:

				$ret[] = GD_URE_TEMPLATE_LAYOUT_PROFILEINDIVIDUAL_DETAILS;
				break;

			case GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS:

				$ret[] = GD_URE_TEMPLATE_LAYOUT_PROFILEORGANIZATION_DETAILS;
				break;
		}

		return $ret;
	}

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS:
			case GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS:

				$ret[] = GD_URE_TEMPLATE_MESSAGE_NODETAILS;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS:

				return 'has-individual-details';

			case GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS:

				return 'has-organization-details';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_SidebarComponentsWrappers();