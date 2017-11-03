<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_WIDGETWRAPPER_COMMUNITIES', PoP_TemplateIDUtils::get_template_definition('ure-widgetwrapper-communities'));
define ('GD_URE_TEMPLATE_WIDGETCOMPACTWRAPPER_COMMUNITIES', PoP_TemplateIDUtils::get_template_definition('ure-widgetcompactwrapper-communities'));

class GD_URE_Template_Processor_SidebarComponentsWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_WIDGETWRAPPER_COMMUNITIES,
			GD_URE_TEMPLATE_WIDGETCOMPACTWRAPPER_COMMUNITIES,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_WIDGETWRAPPER_COMMUNITIES:

				$ret[] = GD_URE_TEMPLATE_WIDGET_COMMUNITIES;
				break;

			case GD_URE_TEMPLATE_WIDGETCOMPACTWRAPPER_COMMUNITIES:

				$ret[] = GD_URE_TEMPLATE_WIDGETCOMPACT_COMMUNITIES;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_URE_TEMPLATE_WIDGETWRAPPER_COMMUNITIES:
			case GD_URE_TEMPLATE_WIDGETCOMPACTWRAPPER_COMMUNITIES:

				return 'has-active-communities';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_SidebarComponentsWrappers();