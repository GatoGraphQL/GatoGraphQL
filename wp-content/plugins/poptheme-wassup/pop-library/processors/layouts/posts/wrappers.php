<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION', PoP_TemplateIDUtils::get_template_definition('multicomponentwrapper-userhighlightpostinteraction'));
define ('GD_TEMPLATE_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION', PoP_TemplateIDUtils::get_template_definition('multicomponentwrapper-userpostinteraction'));

class Wassup_Template_Processor_MultipleComponentLayoutWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION,
			GD_TEMPLATE_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:

				$ret[] = GD_TEMPLATE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION;
				break;

			case GD_TEMPLATE_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION:

				$ret[] = GD_TEMPLATE_MULTICOMPONENT_USERPOSTINTERACTION;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
			case GD_TEMPLATE_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION:

				return 'published';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_MultipleComponentLayoutWrappers();